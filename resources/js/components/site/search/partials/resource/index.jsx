import React ,{ Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form,Image} from 'react-bootstrap';


import {Redirect , Link ,useHistory} from "react-router-dom";

import { connect } from 'react-redux'
import { Player , ControlBar } from 'video-react'



// get our fontawesome imports
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faHome , faDownload } from "@fortawesome/free-solid-svg-icons";



class Resource extends Component{


    constructor(props) {
      super(props);
      this.state = { 
        playVideo:null,
        redirect: null,
        resource:[],  
      };
         
    }

    componentDidMount() {
       
    }

    componentDidUpdate(prevProps) {
    
    }

    

    handleRedirectToProduct = (url) => { 
      this.setState({redirect: url});    
    }

    handlePlayIfVideo = (resourceType) => {
      if( resourceType=="video" || resourceType=="Video" ){
        this.setState({playVideo: true});  
      }
    }

  render () {

    const resource = this.props.resource;
    const playVideo= this.state.playVideo;
    
    if( this.state.redirect != null ){
      return <Redirect to={this.state.redirect} />
    }
    if(playVideo){

    
    return (
          
                  <Col md={3} onClick={() => this.handleRedirectToProduct(resource.url)}>
                    <Player  autoPlay={true} poster={asset_url()+"/resources/images/original/"+ ( resource.searchable.images?.[0]?.url ??  "not-found.jpg")}>
                      <source src={asset_url()+"/resources/files/"+(resource.searchable.files?.[0]?.url)} />
                      <ControlBar autoHide={false} />
                    </Player> 
                  </Col>
          );
    }
    else{
       return (
                <Col md={3}  onMouseEnter={() => this.handlePlayIfVideo(resource.searchable?.category?.title)} onMouseLeave={() => (resource.searchable?.category?.title)}>  
                    <Card  onClick={() => this.handleRedirectToProduct(resource.url)}  >
                      
                      <Card.Img variant="top" src={ asset_url()+"/resources/images/original/"+ ( resource.searchable.images?.[0]?.url ??  "not-found.jpg")} />
                      
                      <Card.Body >
                        <Card.Title> { resource.title} </Card.Title>
                        <div key={resource.title} >
                        <FontAwesomeIcon icon={faEye}  />
                        </div>  
                      </Card.Body>

                    </Card>
                </Col>
             


        
              
        

             
       

      
    );
  }
}
}


 function mapStateToProps(state){
   return {  
    }
 }

export default connect(mapStateToProps)(Resource)
  