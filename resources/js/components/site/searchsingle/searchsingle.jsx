import React ,{useState , useEffect ,Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Image,Badge} from 'react-bootstrap';
import {Link, Redirect ,useHistory} from "react-router-dom"
import { connect } from 'react-redux'
import queryString from 'query-string'
import axios from 'axios';
import './../searchsingle/searchsingle.css';
import searchresult from '../../assets/img/searchresult.jpg'; 
import { Player , ControlBar } from 'video-react'
import { getResourceAction, }  from "./../../../actions/resourceActions";
import moment from "moment"
// get our fontawesome imports
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faHome , faDownload } from "@fortawesome/free-solid-svg-icons";
import Searchbar from '../search/partials/searchbar/';
import {MoonLoader} from "react-spinners";
import { css } from "@emotion/core";



class Searchsingle extends Component{

  constructor(props) {
        
        super(props);
        this.state = {
            resource:'',
            redirect:null,
        };

        
        this.handler = this.handler.bind(this); 
  }



  componentDidMount() {
      const values = queryString.parse(this.props.location.search);
        if(values.id != null || values.id != undefined){
          this.setState({loading:true});    
          this.props.dispatch(getResourceAction(values.id));  
        }
  }

  redirectToSearch = (keywords) => {
      
      return {
        pathname:"/search",
        search:"",
        state: {
          type: (this.state.resource?.category?.id ?? "1"),
          keywords: keywords
        }
      }

  }

  componentDidUpdate(prevProps) {
   
    if (this.props.resource !== prevProps.resource) {
        this.setState({loading:false});
        this.setState({resource:this.props.resource});
    }
  }

   

  handleDownload = (resource) => {
      //window.open(app_url+"/site/file/download/"+downloadableType+"/"+downloadableId);
      console.log(resource);
      
      var resourceType = resource.category?.title;
      var url=""; 
      if(resourceType == 'image-photo' || resourceType == 'image-vector' || resourceType == 'image-illustration' ){
        var downloadable_type  = "image";
        if(resource.resource.sourceable_downlaod_link != "" ){
            url = resource.resource.sourceable_download_link;
        }else{ 
            url = app_url+"/site/file/download/"+downloadableType+"/"+resource.resource.images[0].id;
        }
      }
      
      if( resource.resource?.downloads){
        resource.resource.downloads = resource.resource.downloads+1;
        this.setState({resource:resource});
      }
      
      window.open(url);
  } 
  
  handleDemo = (url) => {
     
     //if(url != null){
        window.open(url);   
     //} 
      
  } 
  
  handler = (types ,keywordss) => {
     
    const location = {
      pathname:"/search",
      state:{
        type:types,
        keywords:keywordss
      }
    }
    this.setState({redirect:location});
  }   
 
  render () {

    if(this.state.redirect){
      return <Redirect push to={this.state.redirect}/>
    }

    const resource = this.state.resource?.resource;
    console.log(resource);
    if( resource == '' || resource == undefined){
        return (
            <div className="singleresultfile">  

                <span className="search-single-content-div">
                    <Searchbar handler={this.handler}  />
                    <div className="loader-results">
                        <MoonLoader
                          size={150}
                          color={"#123abc"}
                          loading={this.state.loading}
                        />
                    </div>
                </span>
                <br/>
                <Container>
                
                    <Row className="searhresultsingle">
                        <div className="loader-results">
                        
                        </div>
                    </Row>
                </Container>
             </div> 
        );
    }else{

    const resourceType = resource.category?.title;
      
    return (
     

     <div className="singleresultfile">  

      <span className="search-single-content-div">
               <Searchbar handler={this.handler}  /></span>
            <Container>

          <br/>

          <Row className="searhresultsingle">
             
              <Col lg={8} className="searhresultsingleimg">
                
                
                {  /** If Video**/
                    (resourceType == "video" || resourceType == "Video")  && <Player  autoPlay={true} poster={resource.images?.[0]?.url  ?  (asset_url()+"/resources/images/small/"+ ( resource.images?.[0]?.url))  : resource.image }>
                      <source src={ resource.files?.[0]?.url ?  (asset_url()+"/resources/files/"+(resource.files?.[0]?.url) ) : resource.preview_video_url } />
                      <ControlBar autoHide={false} />
                    </Player>
                }
                
                { /** If Not Video**/
                
                (resourceType != "video" && resourceType != "Video")  &&  
                  <Image src={ resource.images?.[0]?.url  ?  (asset_url()+"/resources/images/small/"+ ( resource.images?.[0]?.url))  :   ( resource.image ??    (asset_url()+"/resources/images/small/"+"not-found.png"  ))} rounded />
                }
                
                
                                    { ( (resourceType == "plugin" || resourceType == "theme" ) 
                                && resource.demo_url   != null ) && <div className="col-md-12"><Button className="demo-button rajaex-kk" variant="primary" onClick={() => this.handleDemo(resource.demo_url)}  >Demo<FontAwesomeIcon icon={faEye} /></Button></div> }
                  
              </Col>
              <Col lg={4}  className="badgemain">

              <h2>{"Related Keywords"}</h2>
              <span className="keyworsdiv">
              <hr/>
                   { resource.keywords != undefined  && 
                        resource.keywords.map((keyword,i) => {
                             if(i >= 10){
                                 return ""
                             }    
                             return <Link to={() => this.redirectToSearch(keyword) } key={keyword}>
                              <span className="badge label-info"  ><h3><Badge variant="secondary">{keyword}</Badge></h3>  </span></Link>
                        })
                    }   
                 </span>
                   
                <hr/>
                <span className="photodis"> 
                  {/*<p><strong>Largest Size: </strong>Lorem Ipsum is simply dummy text</p>*/}
                  <p><strong>Photo ID: </strong>{'RS-100-'+resource.id}</p>
                  <p><strong>Created Date: </strong>{moment(resource.created_at).format("dddd, MMMM Do YYYY")}</p>
                </span>

                   <div className="numofdownloads"> 
                    <p><FontAwesomeIcon icon={faDownload}/> <strong>{ resource.downloads}</strong></p>
                   </div>
                   <div className="numofdownloads"> 
                    <p><FontAwesomeIcon icon={faEye}/> <strong>{ resource.views}</strong></p>
                   </div>
                   { ( (resourceType == "image-photo" || resourceType == "image-vector" || resourceType == "image-illustration")  
                                && resource.images !=[] ) && <Button variant="primary" onClick={() => this.handleDownload(resource)}>Download Now <FontAwesomeIcon icon={faDownload} /></Button> }
                   { ( (resourceType != "image-photo" && resourceType != "image-vector" && resourceType != "image-illustration") 
                                && resource.files   !=[] ) && <Button variant="primary" onClick={() => this.handleDownload(resource)}  >Download Now <FontAwesomeIcon icon={faDownload} /></Button> }
                   
              </Col>

              <Col lg={8} md={8} className="searhresultsinglecontent">
                    
                    <h2>{resource.title}</h2>
                    <p>{resource.description}</p>
                                 
              </Col>


              </Row>

              <Row className="relatedimgs">
               
                {/*{() => {
                    const images = resource.resource.images;
                      if(images == []){
                        return (
                          <div >
                              <Col md={3}>
                                  <Card >
                                    <Card.Img variant="top" src={searchresult} />        
                                </Card>
                              </Col>
                          </div>
                        );
                      }else
                      {
                        images.map( (image , i) => {
                          return  (<div><Col md={3}>
                            <Card >
                                <Card.Img variant="top" src={image.url }  />
         
                            </Card>
                          </Col></div>);
                        } )  
                    }
                }}*/}
              </Row>
         </Container>
      </div> 
    );
  }
  }
}

const mapStateToProps = (state) => {
  return {
    resource : state.resourceReducer.resource
  }
}

export default connect(mapStateToProps)(Searchsingle);
