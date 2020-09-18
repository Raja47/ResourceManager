import React from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Navbar,Nav,Form,FormControl,NavDropdown} from 'react-bootstrap';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";
import './header.css';

// import logo from '../../../assets/img/logo-new.png'; 



function Header() {
  return (
    <Row className="header">
      <Col sm={4}>
    
          <Navbar bg="light" expand="lg">
                <Navbar.Toggle aria-controls="basic-navbar-nav" />
                <Navbar.Collapse id="basic-navbar-nav">
                  <Nav className="mr-auto">
                  <Link to="/">Home</Link>
                  <Link to="/search">Search</Link>
                    <NavDropdown title="Dropdown" id="basic-nav-dropdown" >
                      <NavDropdown.Item href="#action/3.1">Action</NavDropdown.Item>
                      <NavDropdown.Item href="#action/3.2">Another action</NavDropdown.Item>
                      <NavDropdown.Item href="#action/3.3">Something</NavDropdown.Item>
                      <NavDropdown.Divider />
                      <NavDropdown.Item href="#action/3.4">Separated link</NavDropdown.Item>
                    </NavDropdown>
                  </Nav>
                  {/* <Form inline>
                    <FormControl type="text" placeholder="Search" className="mr-sm-2" />
                    <Button variant="outline-success">Search</Button>
                  </Form> */}
                </Navbar.Collapse>
              </Navbar>
      </Col>
      <Col sm={4} className="headermenu">

          {/*  <img src={logo} />
*/}
      </Col>

      <Col sm={4} className="getbtnsection">

      <Navbar bg="primary" variant="dark" className="menu-bg">
            <Navbar.Brand href="#home"></Navbar.Brand>
            <Nav className="mr-auto">
            <Link to="/searchsingle">Search Result</Link>
            <Link to="/search">JOIN US</Link>
            </Nav>
        </Navbar>
      </Col>
    </Row>
  );
}

export default Header;
