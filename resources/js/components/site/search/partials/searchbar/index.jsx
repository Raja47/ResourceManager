import React ,{Component, Fragment} from 'react';
import { Redirect} from "react-router-dom";
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet,Form, Navbar,Nav,NavDropdown} from 'react-bootstrap';
import './searchbar.css';
import icon from '../../../../assets/img/icon.png'; 
import Select from "react-select";
// get our fontawesome imports
import { faSearch ,faEye} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

class Searchbar extends Component {


  constructor(props) {
      super(props);

      this.state = {
          resources:[],
          searchKeywords:"",
          selectedType : '1',
          options: [
             { value: '1',  label:'Image'  },
             { value: '2',  label:'Video'  },
             { value: '3', label:'plugin' }
          ]  
      };

  }

  componentDidMount() {      
      
  }

  componentDidUpdate(prevProps) {
    // Typical usage (don't forget to compare props):
    if (this.props.resources !== prevProps.resources) {
      this.setState({resources:this.props.resources});
    }
  }


  /**
   * { search Input & select Type . Change }
   */
  
  handleChangeType = (e) => {
    alert(e.value);
    this.setState({'selectedType':e.value})
  }

  handleChangeKeywords = (e) => {
    this.setState({searchKeywords: e.target.value});
    
  }
  
  handleSearhClick = () => {
    var {searchKeywords , selectedType } = this.state;
    if(searchKeywords !== "" ){
        this.setState({redirect : "/search"}) 
    } 
  }

  render() {
    
    if (this.state.redirect) {

      var {searchKeywords , selectedType } = this.state;
      return <Redirect 
                to={{
                    pathname: this.state.redirect,
                    state: { keywords: searchKeywords , type: selectedType  }
                }}
                />
    }

    return (
      <Row className="slidermain">
        
        <Col md={12}>
        
        
        <Row>
        <Col md={2}></Col>
        <Col md={8} className="formfirstcontent">
          <img src={icon} />
          
          <Form.Control 
            name="searchKeywords"
            type="text" 
            onChange={ e => {this.handleChangeKeywords(e)}}
            value={this.state.searchKeywords}
            placeholder="Find the perfect eWorldTrade photos, Videos and More..." 
            autoComplete="off"
          />
          <span className="searchsugggestions">
            <p>C</p>
            <p>C</p>
            <p>C</p>
            <p>C</p>
            <FontAwesomeIcon icon={faSearch} />
            <strong className="Search-tip">Search tip</strong>
            <span className="Search-tip-span">Try the <strong>Fresh content</strong> sort for our newest images.</span>
          </span>
          <Select
            name="type" 
            placeholder="Select Type" 
            options={this.state.options}
            defaultValue={{ label: "Image", value: "1" }}
            onChange={ e => {this.handleChangeType(e)}}
          />

          <Button 
            className="getbtn"
            variant="warning"
            onClick = {this.handleSearhClick}  
          >SEARCH</Button>

        </Col>
        <Col md={2}></Col>
        </Row>
         
        </Col>
        <h1>You can Find the perfect eWorldTrade photos, Videos and More... Without investing anything</h1>
      </Row>
    );
  }
}

export default Searchbar;