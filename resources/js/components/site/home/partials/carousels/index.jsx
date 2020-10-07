import React ,{Component, Fragment} from 'react';
import { Link, Redirect,useHistory} from "react-router-dom";
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet,Form, Navbar,Nav,NavDropdown} from 'react-bootstrap';
import './carousels.css';

import { connect } from 'react-redux'
import icon from '../../../../assets/img/icon.png'; 
import Select from "react-select-search";
// get our fontawesome imports
import { faSearch ,faEye} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import SelectSearch from "react-select"

import { suggestResourceAction }  from "../../../../../actions/resourceActions"; 

class Carouselslider extends Component {


  constructor(props) {
      super(props);

      this.state = {
          resources:[],
          type: 'group',
          searchKeywords:"",
          selectedType : '1',
          suggestions : [],
          suggestedKeywords: [],
          options:[
             { value: '0',  name:'All'  }, 
             { value: '1',  name:'Image'  },
             { value: '2',  name:'Video'  },
             { value: '3',  name:'Plugin' },
             { value: '4',  name:'Theme' },
                
          ]
      };


  }

  componentDidMount() {      
      
  }

  componentDidUpdate(prevProps) {
    // Typical usage (don't forget to compare props):
    if (this.props.suggestions !== prevProps.suggestions) {
      this.setState({suggestions:this.props.suggestions});
    }

    if (this.props.suggestedKeywords !== prevProps.suggestedKeywords) {
      this.setState({suggestedKeywords:this.props.suggestedKeywords});
    }
  }


  /**
   * { search Input & select Type . Change }
   */
  handleEnterKey = (e) => {
    
     if(e.keyCode === 13){
        const {searchKeywords } = this.state;
        
          if(searchKeywords !== "" ){
              this.setState({redirect : "/search"}) 
          }
      }
  }

  handleChangeType = (e) => {
    this.setState({'selectedType':e})
  }

  handleChangeKeywords = (e) => {
      
      this.setState({searchKeywords:e});
      if(e.value !== "" || e.value !== undefined){
          this.setState({redirect : "/search"})  
      }
      
  }

  handleTypedKeywords = (e) => {

    if( e =="" || e == undefined){
        
         this.setState({suggestedKeywords:[]});
    }else{

      var {selectedType } = this.state;
      this.props.dispatch(suggestResourceAction(selectedType,e));
      this.setState({searchKeywords:{label:e , value:e}});  
    }
  }
  
  handleSearhClick = () => {
    var {searchKeywords , selectedType } = this.state;
    // console.log()
    if(searchKeywords !== "" ){
        useHistory.push({
                  pathname: "/search",
                  state: { keywords: searchKeywords.value , type: selectedType  }
        });
        // this.setState({redirect : "/search"}) 
    } 
  }

  handleClick = (e) => {
    this.inputElement.click();
  }
  /**
   * Renders the Component.
   *
   * @return {<Html>}  { return reactable html on web page}
  */
  render() {
    
    if (this.state.redirect) {
      var { searchKeywords , selectedType } = this.state;
      return <Redirect
              to={{
                  pathname: this.state.redirect,
                  state: { keywords: searchKeywords.value , type: selectedType  }
              }}
             
              />

    }

    const {suggestions,suggestedKeywords} = this.state;
    

    return (
      <Row className="slidermain">
        
        <Col md={12}>
         
        <Row>
        <Col md={2}></Col>
        {/* <p class="line-1 anim-typewriter">The internet’s source of freely-usable images.</p> */}
        <Col md={8} className="formfirstcontent">
       
          <Row>
             <Col lg={3} xs={12} md={12} className="selecttype4"> 
              <Select
                name="type" 
                placeholder="Select Type" 
                options={this.state.options}
                onChange={ e => {this.handleChangeType(e)}}
              />
            </Col>
            <Col lg={9} xs={12} md={12} className="searchmain-home"> 
              <SelectSearch 
                onInputChange={e => {this.handleTypedKeywords(e)}}
                onKeyDown={ e => {this.handleEnterKey(e)} }
                onChange={  e  => {this.handleChangeKeywords(e)}} 
                options={this.state.suggestedKeywords} 
                placeholder="Type Your keywords" 
                className="form-control"
                value={this.state.searchKeywords}
              />
             
            </Col>
            <FontAwesomeIcon icon={faSearch}  onClick = {this.handleSearhClick} className="getbtn"/>
          </Row>
              
          </Col>

          <Col md={2}></Col>

        </Row>
         
        </Col>
        <h1>You can Find the perfect eWorldTrade photos, Videos and More... Without investing anything</h1>
      </Row>
    );
  }
}


function mapStateToProps(state){
   return {  
        suggestions: state.resourceReducer.suggestedResources,
        suggestedKeywords: state.resourceReducer.suggestedKeywords
    }
 }


export default connect(mapStateToProps)(Carouselslider)