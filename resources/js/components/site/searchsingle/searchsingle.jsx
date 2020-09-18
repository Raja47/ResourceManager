import React ,{useState , useEffect} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Image} from 'react-bootstrap';
import axios from 'axios';
import './../searchsingle/searchsingle.css';
import searchresult from '../../assets/img/searchresult.jpg'; 


function Searchsingle() {  
  return (
   <div>  
      <Container>
       
        <Row className="searhresultsingle">
           
            <Col md={8} className="searhresultsingleimg">
                <Image src={searchresult} rounded />
                </Col>
                <Col md={4}>
               
                 <h2>Test</h2>
                <p>Some quick example text to build on the card title and make up the bulk of
                          the card's content.</p>
                    
                </Col>
            </Row>

            <Row className="relatedimgs">
              <h2>Related Photos</h2>
              <Col md={3}>
                    <Card >
                        <Card.Img variant="top" src={searchresult} />
                      </Card>
              </Col>
              <Col md={3}>
                    <Card >
                        <Card.Img variant="top" src={searchresult} />
 
                      </Card>
              </Col>
              <Col md={3}>
                    <Card >
                        <Card.Img variant="top" src={searchresult} />
                        
                      </Card>
              </Col>
              <Col md={3}>
                    <Card >
                        <Card.Img variant="top" src={searchresult} />
                       
                      </Card>
              </Col>

            </Row>
       </Container>
    </div> 
  );
}

export default Searchsingle;
