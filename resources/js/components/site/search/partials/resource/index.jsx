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
      let hoveredVideo = "";
      this.play = this.play.bind(this);
      this.pause = this.pause.bind(this);     
    }
    
    
    /**
     * Video Related Functions
     **/
    componentDidMount(){
       if(this.props.resource?.searchable?.category_title == "video"){
           this.player.subscribeToStateChange(this.handleStateChange.bind(this));
           if(this.props.position === 0){
            this.playDefault();
           }
       }
    }
    
    handleStateChange(state) {
        // copy player state to this component's state
        this.setState({
          player: state
        });
          
    }
    
    play() {
        this.hoveredVideo = setTimeout( () => {  this.player.play() } , 800 );

    }
    
    playDefault(){
         this.player.play() 
    }
    
    pause() {
        clearTimeout(this.hoveredVideo);
        this.player.pause();
    }    
    
    
    /**
     *  Redirect if someone click on video   
     **/
    handleRedirectToProduct = (url) => { 
      this.setState({redirect: url});    
    }


    /**
     * Component Rendering 
     * 
    **/
    render () {

        const resource = this.props.resource;
        const playVideo= this.state.playVideo;
       
        if( this.state.redirect != null ){
          return <Redirect to={this.state.redirect} push />
        }
        {/**
            Video or 
        **/}
        if(resource.searchable?.category_title == "video"){
            
            return (
                <Col md="auto" className="img5555 video-kk" onClick={ () => this.handleRedirectToProduct("resource?id="+resource.searchable?.id) }  >
                    <Card onMouseEnter={() => { this.play()} } onMouseLeave={ () => {this.pause()} }>
                      <Player  key={resource.searchable.uploaded_file_url ? asset_url()+"/resources/files/"+ resource.searchable.uploaded_file_url  :  resource.searchable.preview_video_url} ref={player => {this.player = player;}} preload={"none"} autoPlay={false}  poster={resource.searchable.images?.[0]?.url  ?  (asset_url()+"/resources/images/small/"+ ( resource.searchable.images?.[0]?.url))  :   ( resource.searchable.image ??    (asset_url()+"/resources/images/small/"+"not-found.png"  )) }>
                        <source src={ resource.searchable.uploaded_file_url ? asset_url()+"/resources/files/"+ resource.searchable.uploaded_file_url  :  resource.searchable.preview_video_url  } />
                      </Player> 
                    </Card>
                </Col>
            );
            
        }
        else{
            
            return (
                <Col md="auto" className={ (resource.searchable?.category_title == "theme"  || resource.searchable?.category_title == "plugin" ) ? "img5555 img-plugin-kk" : "img5555"}>  
                    <Card  onClick={ () => this.handleRedirectToProduct("resource?id="+resource.searchable.id) }  >
                      <Image variant="top" thumbnail  src={ resource.searchable.uploaded_image_url  ?  (asset_url()+"/resources/images/small/"+ (resource.searchable.uploaded_image_url))  :   ( resource.searchable.image ??    (asset_url()+"/resources/images/small/"+"not-found.png"  ))  } />
                      <Card.Body >
                        <Card.Title> { resource.searchable?.title} </Card.Title>
                            <div key={resource.searchable?.title} >
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
   return { }
}

export default connect(mapStateToProps)(Resource)
  