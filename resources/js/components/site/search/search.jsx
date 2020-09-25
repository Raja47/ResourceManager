import React ,{ Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form,Image} from 'react-bootstrap';
import './search.css';
import Searchimg from './../../assets/img/searchimg.jpg'; 
import { connect } from 'react-redux'

import { searchResourceAction ,}  from "./../../../actions/resourceActions";



class Search extends Component{


    constructor(props) {
      super(props);
      this.state = { 
        keywords : '',
        type : '',
        redirect: null,
        resources:[],  
      };
        
    }
    
    componentDidMount() {
      
        if(this.props.location.state != undefined){
          
          const {keywords , type} = this.props.location.state;
          this.props.dispatch(searchResourceAction(type,keywords));
        }
        // keywords: this.props.location.state == undefined ? '' : this.props.location.state.keywords,
        // type : this.props.location.state == undefined ? '' : this.props.location.state.type,
       
        //this.props.location.state.keywords  
    }

    componentDidUpdate(){
      
    }


  render () {

    
    return (
      <Row className="searhresultsec">
         <Col md={12}> </Col>
         <Col md={12}> 
          <Form.Control type="email" placeholder="Find the perfect eWorldTrade photos, Videos and More..." />
         </Col>
         <Col md={12}> </Col>
         <Col md={3}>
            <Card >
                <Card.Img variant="top" src={Searchimg} />
                <Card.Body>
                  <Card.Title> Title</Card.Title>
                 
                  <Button variant="primary">Go somewhere</Button>
                </Card.Body>
              </Card>
          </Col>
          <Col md={3}>
            <Card >
                  <Card.Img variant="top" src={Searchimg} />
                  <Card.Body>
                    <Card.Title> Title</Card.Title>
                    
                    <Button variant="primary">Go somewhere</Button>
                  </Card.Body>
              </Card>
          </Col>
          <Col md={3}>
              <Card >
                  <Card.Img variant="top" src={Searchimg} />
                  <Card.Body>
                    <Card.Title> Title</Card.Title>
                    
                    <Button variant="primary">Go somewhere</Button>
                  </Card.Body>
              </Card>
          </Col>  
          <Col md={3}>
              <Card>
                  <Card.Img variant="top" src={Searchimg} />
                  <Card.Body>
                    <Card.Title> Title</Card.Title>
                    
                    <Button variant="primary">Go somewhere</Button>
                  </Card.Body>
              </Card>
          </Col>  
          
          <Col md={3}>
            <Card >
                <Card.Img variant="top" src={Searchimg} />
                <Card.Body>
                  <Card.Title> Title</Card.Title>
                  
                  <Button variant="primary">Go somewhere</Button>
                </Card.Body>
              </Card>
          </Col>
          <Col md={3}>
            <Card >
                  <Card.Img variant="top" src={Searchimg} />
                  <Card.Body>
                    <Card.Title> Title</Card.Title>
    
                    <Button variant="primary">Go somewhere</Button>
                  </Card.Body>
              </Card>
          </Col>
          <Col md={3}>
              <Card >
                    <Card.Img variant="top" src={Searchimg} />
                    <Card.Body>
                      <Card.Title> Title</Card.Title>
                      
                      <Button variant="primary">Go somewhere</Button>
                    </Card.Body>
              </Card>
          </Col>  
          <Col md={3}>
              <Card>
                    <Card.Img variant="top" src={Searchimg} />
                    <Card.Body>
                      <Card.Title> Title</Card.Title>
                      
                      <Button variant="primary">Go somewhere</Button>
                    </Card.Body>
              </Card>
          </Col>   
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
  