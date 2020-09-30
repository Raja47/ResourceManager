import React ,{ Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form,Image} from 'react-bootstrap';
import './search.css';

import {Redirect , Link ,useHistory} from "react-router-dom";
import Searchimg from './../../assets/img/searchimg.jpg'; 
import { connect } from 'react-redux'

import { searchResourceAction ,}  from "./../../../actions/resourceActions";
import Searchbar from './partials/Searchbar/';

// get our fontawesome imports
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faHome , faDownload } from "@fortawesome/free-solid-svg-icons";



class Search extends Component{


    constructor(props) {
      super(props);
      this.state = { 
        keywords : '',
        type : '',
        redirect: null,
        resources:[],  
      };
      this.handler = this.handler.bind(this);    
    }

    componentDidMount() {
        if(this.props.location.state != undefined){
          const {keywords , type } = this.props.location.state;
          this.props.dispatch(searchResourceAction(type,keywords));
        }
    }

    componentDidUpdate(prevProps) {
    // Typical usage (don't forget to compare props):
      if (this.props.resources !== prevProps.resources) {
        this.setState({resources:this.props.resources });
      }
    }

    handler = (type ,keywords) => {
        this.props.dispatch(searchResourceAction(type,keywords));   
    }

    handleRedirectToProduct = (url) => { 
      this.setState({redirect: url});    
    }


  render () {
    const resources = this.state.resources;
    if( this.state.redirect != null ){
      return <Redirect to={this.state.redirect} />
    }
    return (
      <Row className="searhresultsec">
         
         <Col md={12}> 
           <Searchbar handler={this.handler}  /><br/>
         </Col>
         <Col md={12}> </Col>
         { resources.map((resource,i) => {

                 return <Col md={3} key={i}>
                            
                            <Card >
                              
                              <Card.Img variant="top" src={ asset_url()+"/resources/images/original/"+ ( resource.searchable.images?.[0]?.url ??  "not-found.jpg")} />
                              
                              <Card.Body>
                                <Card.Title> { resource.title} </Card.Title>
                                <div key={resource.title} onClick={() => this.handleRedirectToProduct(resource.url)}>
                                <FontAwesomeIcon icon={faEye}  />
                                </div>  
                              </Card.Body>

                            </Card>
                        </Col>

            })}

      </Row>
    );
  }
}


 function mapStateToProps(state){
   return {  
        resources: state.resourceReducer.searchedResources, 
       
    }
 }

export default connect(mapStateToProps)(Search)
  