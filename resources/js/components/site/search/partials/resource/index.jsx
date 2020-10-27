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

    handleStopIfVideo = (resourceType) => {
      if( resourceType=="video" || resourceType=="Video" ){
        this.setState({playVideo: false});  
      }
    }
  render () {

    const resource = this.props.resource;
    const playVideo= this.state.playVideo;
    
    if( this.state.redirect != null ){
      return <Redirect to={this.state.redirect} push />
    }
    if(playVideo){

    
    return (
                 
            <Col md="auto" className="img5555" onClick={() => this.handleRedirectToProduct(resource.url)} onMouseLeave={() => this.handleStopIfVideo(resource.searchable?.category?.title)}>
            <Card>
              <Player  autoPlay={true} poster={asset_url()+"/resources/images/small/"+ ( resource.searchable.images?.[0]?.url ??  "not-found.png")}>
                <source src={asset_url()+"/resources/files/"+(resource.searchable.files?.[0]?.url)} />
                <ControlBar autoHide={false} />
              </Player> 
              </Card>
            </Col>

          );
    }
    else{
       return (

               
                <Col md="auto" className="img5555" onMouseEnter={() => this.handlePlayIfVideo(resource.searchable?.category?.title)} >  
                    <Card  onClick={() => this.handleRedirectToProduct(resource.url)}  >
                      
                      <Image variant="top" thumbnail  src={ resource.searchable.image == "" ? (asset_url()+"/resources/images/small/"+ ( resource.searchable.images?.[0]?.url ??  "not-found.png")) : resource.searchable.image } />
                      
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
  