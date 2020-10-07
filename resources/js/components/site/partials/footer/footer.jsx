import React from 'react';
import {Button, Carousel ,Container ,Row,Col,Jumbotron} from 'react-bootstrap';

import './footer.css';
import logo from './../../../assets/img/logo.png'; 

function Footer() {
  return (
 <div className="FootermainRow">
   <Container> 
{/* <Row className="footerlinks">
  <Col md={3}>
  <h3>Links</h3>
<ul>
  <li><a href="#">About us</a></li>
<li><a href="#">Buy VPN</a></li>
<li><a href="#">Download VPN</a></li>
<li><a href="#">Business VPN</a></li>
<li><a href="#">Become an Affiliate</a></li>
<li><a href="#">Become a Reseller</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Support Center</a></li>
<li><a href="#">Student And Apprentice Discount</a></li>
<li><a href="#">Sitemap</a></li>
</ul>
  </Col>

  <Col md={3}>
  <h3>Products</h3>
  <ul>
  <li><a href="#">About us</a></li>
<li><a href="#">Buy VPN</a></li>
<li><a href="#">Download VPN</a></li>
<li><a href="#">Business VPN</a></li>
<li><a href="#">Become an Affiliate</a></li>
<li><a href="#">Become a Reseller</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Support Center</a></li>
<li><a href="#">Student And Apprentice Discount</a></li>
<li><a href="#">Sitemap</a></li>
</ul>
  </Col>

  <Col md={3}>
  <h3>Most Popular</h3>
  <ul>
  <li><a href="#">About us</a></li>
<li><a href="#">Buy VPN</a></li>
<li><a href="#">Download VPN</a></li>
<li><a href="#">Business VPN</a></li>
<li><a href="#">Become an Affiliate</a></li>
<li><a href="#">Become a Reseller</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Support Center</a></li>
<li><a href="#">Student And Apprentice Discount</a></li>
<li><a href="#">Sitemap</a></li>
</ul>
  </Col>

  <Col md={3}>
  <h3>Learn More</h3>
  <ul>
  <li><a href="#">About us</a></li>
<li><a href="#">Buy VPN</a></li>
<li><a href="#">Download VPN</a></li>
<li><a href="#">Business VPN</a></li>
<li><a href="#">Become an Affiliate</a></li>
<li><a href="#">Become a Reseller</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Support Center</a></li>
<li><a href="#">Student And Apprentice Discount</a></li>
<li><a href="#">Sitemap</a></li>
</ul>
  </Col>

  </Row> */}
<Row className="copyright">
     
        <Col md={12} className="Footercontent">
         
          
        <p>
        Copyright© 2020. All Rights Reserved. | Powered By eWorldtrade
        </p>
        </Col>
      
        
</Row>
</Container>
</div>     
  );
}

export default Footer;
