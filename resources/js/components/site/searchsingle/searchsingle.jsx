import React ,{useState , useEffect ,Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Image} from 'react-bootstrap';

import { connect } from 'react-redux'
import queryString from 'query-string'
import axios from 'axios';
import './../searchsingle/searchsingle.css';
import searchresult from '../../assets/img/searchresult.jpg'; 
import { getResourceAction, }  from "./../../../actions/resourceActions";

// get our fontawesome imports
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faHome , faDownload } from "@fortawesome/free-solid-svg-icons";



class Searchsingle extends Component{


  constructor(props) {
        super(props);
        this.state = {
            resource:'',
        };
  }

  componentDidMount() {

    const values = queryString.parse(this.props.location.search)
    if(values.id != null || values.id != undefined){
      this.props.dispatch(getResourceAction(values.id));  
    }
        
  }


  componentDidUpdate(prevProps) {
    // Typical usage (don't forget to compare props):
    if (this.props.resource !== prevProps.resource) {
      this.setState({resource:this.props.resource});
    }

  }
  handleDownload = (downloadableType,downloadableId) => {
    alert(downloadableId);
  } 
     

  render () {
    
    const resource = this.state.resource;

    if(resource == ''){
      return (
            <h1>Sorry ! Resource not found</h1>
        )
    }else{
      const resourceType = resource.category.title;
      
    return (
     

     <div>  
        <Container>

          <Row className="searhresultsingle">
             
              <Col md={8} className="searhresultsingleimg">
                  <Image src={asset_url()+"/resources/images/original/"+resource.resource.images[0].url} rounded />
              </Col>
              <Col md={4}>
                 
                   <h2>{resource.resource.title}</h2>
                   
                   <p>{resource.resource.description}</p>
                   
                   { resourceType == "image" && resource.images !=[] && <Button variant="primary" onClick={() => this.handleDownload(resourceType,resource.images[0].id)}><FontAwesomeIcon icon={faDownload} /> Download Now</Button> }
                   { resourceType != "image" && resource.files  !=[] && <Button variant="primary" onClick={() => this.handleDownload(resourceType,resource.files[0].id)}><FontAwesomeIcon icon={faDownload} /> Download Now</Button> }
                     
                  
                   
              </Col>

              <Col md={8}>
                    <h2>{"Related Keywords"}</h2>
                   { resource.resource.keywords != undefined  && 
                         resource.resource.keywords.map(ele => {
                            return <span className="badge label-info">{ele} </span>
                        })
                    }                
                  
              </Col>

              </Row>

              <Row className="relatedimgs">
                <h2>Other Images</h2>
                {() => {
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
                      }else{
                      images.map( (image , i) => {
                        return  (<div><Col md={3}>
                          <Card >
                              <Card.Img variant="top" src={image.url}  />
       
                          </Card>
                        </Col></div>);
                      } )  
                      
                  }
                }}
                
                


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
};



export default connect(mapStateToProps)(Searchsingle);
