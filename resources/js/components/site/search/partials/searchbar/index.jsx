import React ,{Component, Fragment} from 'react';

import { Link, Redirect ,useHistory} from "react-router-dom";
// import { history } from 'history'
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet,Form, Navbar,Nav,NavDropdown} from 'react-bootstrap';
import './searchbar.css';
import { connect } from 'react-redux'
import Select from "react-select-search"
import SelectSearch from "react-select"


import icon from '../../../../assets/img/icon.png'; 

// get our fontawesome imports
import { faSearch ,faEye} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";


import { suggestResourceAction }  from "../../../../../actions/resourceActions"; 


class Searchbar extends Component {


  constructor(props) {
      super(props);

      this.state = {
          resources:[],
          searchKeywords:"",
          selectedType : '1',
          searchedFor: [],
          suggestions : [],
          suggestedKeywords: [],
          options: [
             { value: '1',  name:'Image'  },
             { value: '2',  name:'Video'  },
             { value: '3',  name:'plugin' },
             { value: '3',  name:'Theme' },
          ]  
      };
      
      
  }

  componentDidMount() {      
      
  }

  componentDidUpdate(prevProps) {
    

    if (this.props.suggestions !== prevProps.suggestions) {
      
      this.setState({suggestions:this.props.suggestions});
    }

    if(this.props.searchedFor !== prevProps.searchedFor){
      
      this.setState({searchKeywords:{label:this.props.searchedFor.keywords,value:this.props.searchedFor.keywords},selectedType:this.props.searchedFor.type});
    }

    if (this.props.suggestedKeywords !== prevProps.suggestedKeywords) {

      this.setState({suggestedKeywords:this.props.suggestedKeywords});
    }
  }


  /**
   * { search Input & select Type . Change }
   */
  
  handleChangeType = (e) => {
    alert(e);
    this.setState({'selectedType':e})
  }

  handleChangeKeywords = (e) => {
   
    
    this.setState({searchKeywords:e});
    
  }

  handleEnterKey = (e) => {
    
    if(e.keyCode === 13){
      const {searchKeywords , selectedType } = this.state;
    
      if(searchKeywords !== "" ){
          this.props.handler(selectedType,searchKeywords.value);
      }

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
    const {searchKeywords , selectedType } = this.state;
    
      if(searchKeywords !== "" ){
          this.props.handler(selectedType,searchKeywords.value);
      }
  } 


  render() {
     const {selectedType, searchKeywords} = this.state;
     const {suggestions,suggestedKeywords} = this.state;
   
    return (
      <Row className="slidermain">
        
        <Col md={12}>
        
        
        <Row>
        <Col md={2}> <img src={icon} /></Col>
        <Col md={8} className="formfirstcontent">
         


          <Select
            name="types" 
            placeholder="Select Type" 
            options={this.state.options}
            onChange={ e => {this.handleChangeType(e)}}
            value={this.state.selectedType}
          />
          
          <SelectSearch
              name="keywors"
              onInputChange={e => {this.handleTypedKeywords(e)}}
              onKeyDown={e => {this.handleEnterKey(e)}}
              onChange={e  =>  {this.handleChangeKeywords(e)}} 
              options={suggestedKeywords} 
              placeholder="Type Keywords" 
              className="form-control"
              value={this.state.searchKeywords} 
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


function mapStateToProps(state){
   return {  
        suggestions: state.resourceReducer.suggestedResources ,
        searchedFor : state.resourceReducer.searchedFor,
        suggestedKeywords: state.resourceReducer.suggestedKeywords
    }
 }


export default connect(mapStateToProps)(Searchbar)



