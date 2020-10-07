import React ,{Component, Fragment} from 'react';
import { Link, Redirect } from "react-router-dom";
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet,Form, Navbar,Nav,NavDropdown} from 'react-bootstrap';
import './searchbar.css';
import { connect } from 'react-redux'
import Select from "react-select-search"
import SelectSearch from "react-select"
import icon from '../../../../assets/img/icon.png'; 
import { faSearch ,faEye} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { suggestResourceAction }  from "../../../../../actions/resourceActions"; 



class Searchbar extends Component {

  /**
   * Constructs a new instance.
   *
   * @param      {<type>}  props   The properties
   */
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
      var alreadyCalled = '';
      
  }

  /**
   *   {when follwing props changes , set the New State Value}
   *   seachedFor
   *   Suggestions
   *   Suggested Keywords
   */
  componentDidUpdate(prevProps) {
    
    if(this.props.searchedFor !== prevProps.searchedFor){
        this.setState({searchKeywords:{label:this.props.searchedFor.keywords,value:this.props.searchedFor.keywords},selectedType:this.props.searchedFor.type});
    }
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
      

      /**
       * { function_description }
       *
       * @param      {<type>}  e       { parameter_description }
       */
      handleChangeType = (e) => {
        this.setState({'selectedType':e})
      }
      

      /**
       * { function_description }
       *
       * @param      {string}  e       { parameter_description }
       * @param      {string}  action  The action
       */

      handleTypedKeywords = (e,action) => {
        if( action.action == 'input-change'){
            if( e === "" ){
                this.setState({suggestedKeywords:[]});
                this.setState({searchKeywords:{label:e , value:e}});
            }else{
                var {selectedType } = this.state;
                clearTimeout(this.alreadyCalled);
                this.alreadyCalled = setTimeout( () =>this.suggestions(selectedType,e) ,400);  
                this.setState({searchKeywords:{label:e , value:e}});   
            }
        }  
      }
      suggestions = (type,keywords) => {
          this.props.dispatch(suggestResourceAction(type,keywords));
      }
      

  
  
  
/**
 * { click search or on enter search functions  }
 */
      /**
       * { search button clicked }
       */
      handleSearhClick = () => {
          const {searchKeywords , selectedType } = this.state;
          if(searchKeywords !== "" ){
              this.props.handler(selectedType,searchKeywords.value);
          }
      }

      /**
       * {when any option selected form suggestions}
       * @param  e  <type> Object {e is option selected } 
       */
      handleChangeKeywords = (e) => {
        this.setState({searchKeywords:e});
        if(e !== "" ){
              this.props.handler(this.state.selectedType,e.value);
        }
      } 

      /**
       * { when enter (keycode=13) is clicked }
       *
       * @param      {<type>}  e { e is keyPressed }
       */
      handleEnterKey = (e) => {
        if(e.keyCode === 13){
            const {searchKeywords , selectedType } = this.state;
            if(searchKeywords !== "" ){
                this.props.handler(selectedType,searchKeywords.value);
            }
        }
      }



  /**
   * {Renders the Component}
   */
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
                onInputChange={(e,action) => {this.handleTypedKeywords(e,action)}}
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


/**
 * { Get the updated props from reducers }
 */
function mapStateToProps(state){
   return {  
        suggestions: state.resourceReducer.suggestedResources ,
        searchedFor : state.resourceReducer.searchedFor,
        suggestedKeywords: state.resourceReducer.suggestedKeywords
    }
 }


export default connect(mapStateToProps)(Searchbar)



