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
      const alreadyCalled = '';

  }


  /**
   *   {when follwing props changes , set the New State Value}
   *   Suggestions
   *   Suggested Keywords
   */
  componentDidUpdate(prevProps) {
    // Typical usage (don't forget to compare props):
    if (this.props.suggestions !== prevProps.suggestions) {
      this.setState({suggestions:this.props.suggestions});
    }
    if (this.props.suggestedKeywords !== prevProps.suggestedKeywords) {
      this.setState({suggestedKeywords:this.props.suggestedKeywords});
    }
  }

  handleChangeType = (e) => {
    this.setState({'selectedType':e})
  }

  handleTypedKeywords = (e,action) => {
   
    if( action.action == 'menu-close' || action.action == 'input-blur' ){
      return '';
    }
    if( e =="" || e == undefined){
        
         this.setState({suggestedKeywords:[]});
         this.setState({searchKeywords:{label:e , value:e}});
    }else{

      var {selectedType } = this.state;
      clearTimeout(this.alreadyCalled);
      this.alreadyCalled = setTimeout( () => this.suggestions(selectedType,e) ,400 ); 
      this.setState({searchKeywords:{label:e , value:e}});  
    }
  }

  suggestions = (type,keywords) => {
     this.props.dispatch(suggestResourceAction(type,keywords));
  }
/**
 * { When Search button , enter in search , option is clicked }
 */   
      /**
       * { search button clicked }
       */
      handleSearhClick = () => {
        var {searchKeywords , selectedType } = this.state;
        
        if(searchKeywords.value !== "" ){
            this.setState({redirect:"/search"});
        } 
      }

      /**
       * when any option selected form suggestions}
       * @param  e  <type> Object {e is option selected } 
       */
      handleChangeKeywords = (e) => {
          
          this.setState({searchKeywords:e});
          if(e.value !== "" || e.value !== undefined){
              this.setState({redirect : "/search"})  
          }
          
      }

      /**
      * { when enter (keycode=13) is clicked }
      *
      * @param {<type>}  e { e is keyPressed }
      */
      handleEnterKey = (e) => {
       if(e.keyCode === 13){
            const {searchKeywords } = this.state;
            if(searchKeywords !== "" ){
                this.setState({redirect : "/search"}) 
            }
        }
      }

  /**
   * Renders the Component.
   *
   * @return {<Html>}  { return reactable html on web page}
  */
  render() {
    
    if( this.state.redirect ){
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
                onInputChange={(e,action) => {this.handleTypedKeywords(e,action)}}
                onKeyDown={ e => {this.handleEnterKey(e)} }
                onChange={  e  => {this.handleChangeKeywords(e)}} 
                options={this.state.suggestedKeywords} 
                placeholder="Type Your keywords" 
                className="form-control"
                value={this.state.searchKeywords}
                isClearable={'false'}
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