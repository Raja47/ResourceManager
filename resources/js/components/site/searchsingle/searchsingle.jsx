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
     

  render () {
    
    const resource = this.state.resource;
    if(resource == ''){
      return (
            <h1>Sorry ! Resource not found</h1>
        )
    }else{
    return (
     

     <div>  
        <Container>

          <Row className="searhresultsingle">
             
              <Col md={8} className="searhresultsingleimg">
                  <Image src={""} rounded />
                  </Col>
                  <Col md={4}>
                 
                   <h2>{resource.resource.title}</h2>
                   <p>{resource.resource.description}</p>
                           
                      <Button variant="primary"><FontAwesomeIcon icon={faDownload} /> Download for free</Button>
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
