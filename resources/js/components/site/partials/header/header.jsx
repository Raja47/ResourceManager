import React from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Navbar,Nav,Form,FormControl,NavDropdown} from 'react-bootstrap';
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link
} from "react-router-dom";
import './header.css';

import logo from '../../../assets/img/logo-new.png'; 
import dash from '../../../assets/img/dash.png'; 
// get our fontawesome imports
import { faSearch ,faEye} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";


function Header() {
  const themeLocation = {
    pathname:'/search',
    state: {
      keywords:'All',
      type:'4'
    }
  }

  const imageLocation = {
    pathname:'/search',
    state: {
      keywords:'All',
      type:'1'
    }
  }

  const videoLocation = {
    pathname:'/search',
    state: {
      keywords:'All',
      type:'2'
    }
  }

  const pluginLocation = {
    pathname:'/search',
    state: {
      keywords:'All',
      type:'3'
    }
  }

  return (
    <Row className="header" >
        <Col lg={4} sm={12} xs={12}>
          <Navbar bg="light" expand="lg">
                <Navbar.Toggle aria-controls="basic-navbar-nav" />
                <Navbar.Collapse id="basic-navbar-nav">
                  <Nav className="mr-auto">
                  {/*<Nav.Link><Link to="/">Home</Link></Nav.Link>*/}
                  <Nav.Link><Link to={imageLocation} >Images</Link></Nav.Link>
                  <Nav.Link><Link to={videoLocation} >Videos</Link></Nav.Link>
                  <Nav.Link><Link to={pluginLocation}>Plugins</Link></Nav.Link>
                  <Nav.Link><Link to={themeLocation} >Themes</Link></Nav.Link>
                  <Link target="_blank" to="/admin/login"> <img src={dash} /> DashBoard</Link>
                {/*<NavDropdown title="Search" id="basic-nav-dropdown">
                    <NavDropdown.Item>Search</NavDropdown.Item>
                    <NavDropdown.Item><Link >Images</Link></NavDropdown.Item>
                    <NavDropdown.Item><Link >Videos</Link></NavDropdown.Item>
                    <NavDropdown.Item><Link >Plugins</Link></NavDropdown.Item>
                    <NavDropdown.Item><Link >Themes</Link></NavDropdown.Item>
                  </NavDropdown>*/}

                  </Nav>
                </Navbar.Collapse>
              </Navbar>
      </Col>

      <Col lg={4}  sm={0} xs={0} className="headermenu">
           {/*<Link to="/"><img src={logo} /></Link>*/}
      </Col>

      <Col md={4} className="getbtnsection" sm={12}>
        <Navbar bg="primary" variant="dark" className="menu-bg">
            <Navbar.Brand href="#home"></Navbar.Brand>
            <Nav className="mr-auto">
              <Link target="_blank" to="/admin/login"><img src={dash} /> DashBoard
              
              </Link>
            </Nav>
        </Navbar>
      </Col>
    </Row>
  );
}

export default Header;
