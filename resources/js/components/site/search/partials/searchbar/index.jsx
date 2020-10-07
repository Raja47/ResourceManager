import React ,{Component, Fragment} from 'react';

import { Link, Redirect } from "react-router-dom";
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
             { value: '0',  name:'All'  },
             { value: '1',  name:'Image'  },
             { value: '2',  name:'Video'  },
             { value: '3',  name:'plugin' },
             { value: '4',  name:'Theme' },
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
    
    this.setState({'selectedType':e})

  }

  handleChangeKeywords = (e) => {
   
    this.setState({searchKeywords:e});
    
    if(e !== "" ){
          this.props.handler(this.state.selectedType,e.value);
    }
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

    if( e === "" ){
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
      <Row className="slidermain toppppp">
        
        <Col md={12}>
        
        
        <Row>
        <Col md={2}></Col>
        <Col md={8} className="formfirstcontent searchresulttopbar">
         
        <Row>
          <Col lg={3} xs={12} md={12} className="selecttype4"> 

          <Select
            name="types" 
            placeholder="Select Type" 
            options={this.state.options}
            onChange={ e => {this.handleChangeType(e)}}
            value={this.state.selectedType}
          />
          </Col>

          <Col lg={9} xs={12} md={12} className="searchbarjsx searchmain-home"> 
              <SelectSearch
                name="keywords"
                onInputChange={e => {this.handleTypedKeywords(e)}}
                onKeyDown={e => {this.handleEnterKey(e)}}
                onChange={(e)  =>  {this.handleChangeKeywords(e)}} 
                options={suggestedKeywords} 
                placeholder="Type Keywords" 
                className="form-control"
                value={this.state.searchKeywords} 
                backspaceRemovesValue={true}
                closeMenuOnScroll={true}
            />
              
          </Col>
          <FontAwesomeIcon icon={faSearch}  onClick = {this.handleSearhClick} className="getbtn"/>
        </Row>
             
              
        </Col>
        <Col md={2}></Col>
        </Row>
         
        </Col>
       
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



