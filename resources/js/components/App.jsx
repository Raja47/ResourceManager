import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import { Button, Carousel, Container, Row, Col, Card } from 'react-bootstrap';
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
} from "react-router-dom";
import Header from './site/partials/header/header';
import Footer from './site/partials/footer/footer';
import Search from './site/search/search.jsx';
import Searchsingle from './site/searchsingle/searchsingle';
import Home from './site/home';


class App extends Component {
    render() {
        return (
          
          <div className="App">
            <Container className="MainAppFluid" fluid>
              
            <Router>
              <Header/>
              <Switch>

                    <Route exact path="/" component={Home} />

                    <Route path="/search" component={Search} />
                     
                    <Route path="/searchsingle" component={Searchsingle} />
                      
                </Switch>
               <Footer/>
            </Router>

            </Container>
          </div>
        )
    }
}

export default App
