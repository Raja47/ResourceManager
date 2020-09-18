import React from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form,Image} from 'react-bootstrap';
import './search.css';
import Searchimg from './../../assets/img/searchimg.jpg'; 


function Search() {
  return (
    <Row className="searhresultsec">
      
        <Form.Control type="email" placeholder="Find the perfect eWorldTrade photos, Videos and More..." />
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
  

  export default Search;
  