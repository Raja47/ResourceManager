import React ,{ Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form,Image,Pagination} from 'react-bootstrap';
import './search.css';

import {Redirect , Link ,useHistory} from "react-router-dom";
import Searchimg from './../../assets/img/searchimg.jpg'; 
import { connect } from 'react-redux'
import Resource from "./partials/resource";
import { searchResourceAction ,}  from "./../../../actions/resourceActions";
import Searchbar from './partials/searchbar/';

// get our fontawesome imports
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faHome , faDownload } from "@fortawesome/free-solid-svg-icons";
import { showLoading, hideLoading } from 'react-redux-loading-bar';
import {MoonLoader} from "react-spinners";
import { css } from "@emotion/core";




class Search extends Component{


    constructor(props) {
      super(props);
      this.state = { 
        keywords : '',
        type : '',
        pageCount: '',
        activePage:1,
        pages:[],
        redirect: null,
        paginationResults: 28,
        loading:true,
    };
     
      this.handler = this.handler.bind(this);  
      var pages=[];  
     
    }

    componentDidMount() {

        if(this.props.location.state != undefined){
          const {keywords , type } = this.props.location.state;
          this.props.dispatch(searchResourceAction(type,keywords));
          this.props.dispatch(showLoading());
          
          
        }
        

    }

    componentDidUpdate(prevProps) {
      // Typical usage (don't forget to compare props):
      
      if (this.props.resources !== prevProps.resources) {
        
        if(this.props.resources.length != undefined){ 
          var pages = [];
          for(var i = 1; i <= Math.ceil(this.props.resources?.length/this.state.paginationResults); i++) {
              pages[i] = i;
          }
          this.pages = pages;  
          this.setState({resources:this.props.resources,pageCount:Math.ceil(this.props.resources?.length/this.state.paginationResults)});
          
        }else{
          this.setState({ resources:this.props.resources});
        }
        this.props.dispatch(hideLoading());
        this.setState({loading:false});
        
        
      }
      
      if(this.props.location.state !== prevProps.location.state ){
         this.handler(this.props.location.state.type,this.props.location.state.keywords);
         
      }
      
    }

    /**
     * { function will be called from searchbar child component to update results}
    */
    handler = (type ,keywords) => {
        
        this.props.dispatch(searchResourceAction(type,keywords));
        this.props.dispatch(showLoading());
    }


  
    /**
     * {All pagination handling Functions  }
     */
    handleFirst = () => {
        this.setState({activePage : '1'});
        this.props.dispatch(showLoading());
        setTimeout( () =>{this.props.dispatch(hideLoading()) }, 2000);
    }

    handlePrevious = () => {
      let { activePage } = this.state;
      
      if(activePage !== 1 ){
          this.setState({activePage:activePage-1 });
          this.props.dispatch(showLoading());
          setTimeout( () =>{this.props.dispatch(hideLoading()) }, 2000);
      }
    }

    handleLast = () => {
      this.setState({activePage: this.state.pageCount });
      this.props.dispatch(showLoading());
      setTimeout( () =>{ this.props.dispatch(hideLoading())} , 2000);
    }

    handleNext = () => {
      let { pageCount,activePage } = this.state;
      if(pageCount == activePage){

      }else{
        this.setState({activePage:activePage+1 }) ;
        this.props.dispatch(showLoading());
        setTimeout( () =>{this.props.dispatch(hideLoading())} , 2000);
      }
    }

    handlePageChange = (i) => {
      this.setState({activePage:i});
      this.props.dispatch(showLoading());
      setTimeout( () =>{this.props.dispatch(hideLoading())} , 2000);
    }
    

  /**
   * Renders the Searchbar & Resource Search Results{resources} 
   *
   * @return { renders Page Searchbar & Resources(All Type in Resource Comp) }
   */
  render () {
   
    const {resources,activePage,pageCount,paginationResults} = this.state;
    
    const pagess = this.pages
  
    
    return (
      <span> 
           
            <Row className="searhresultsec">
             <Col md={12}> 
               <Searchbar handler={this.handler}  /><br/>
             </Col>
            <div className="loader-results">
                 <MoonLoader
                  
                  size={150}
                  color={"#123abc"}
                  loading={this.state.loading}
                />
            </div>
            
             {/**
             * { filtering results belonging to activePage only}
             */}
            { resources !== undefined && resources.map((resource,i) => {
                
              if( (i < (paginationResults*activePage)) && (i >= (paginationResults*(activePage-1))) ){
                   return <Resource resource={resource} key={i}/>
              }   
            })}

           
           
          </Row>   
          { resources=='' && <Row><Col md={3}></Col><Col lg={6} className="errormessage"><h1>Sorry No Resource against keywords</h1> <p>404</p></Col><Col md={3}></Col></Row>}
          { resources != '' && resources != undefined && resources != [] && <Row>
              
              <Col md={2}></Col>
              <Col md={8} className="paginationcustome">
                { pageCount != '1' &&                       
                  <Pagination>
                    <Pagination.First onClick={this.handleFirst}/>
                    <Pagination.Prev  onClick={this.handlePrevious} />  
                    {/**
                     * { dynamic page number generetation inside pagination }
                     */}
                     
                    
                    { pagess !== undefined && pagess.map((object,i) => { 
                        if( (i > (activePage-5) &&  i < (activePage+5))){
                            return <Pagination.Item onClick={ () => this.handlePageChange(i)} active={activePage==i ? 'active' : null } key={i}>{i}</Pagination.Item> 
                        }                
                    })}
                   
                    <Pagination.Next onClick={this.handleNext}/>
                    <Pagination.Last onClick={this.handleLast}/><span className="pageCount">of { pageCount }</span>
                  </Pagination>
                 
                }
                
              </Col>
              
              <Col md={2}></Col>  

          </Row>
        }
      </span> 
    );
  }
}


 function mapStateToProps(state){
   return {  
        resources: state.resourceReducer.searchedResources, 
    }
 }

export default connect(mapStateToProps)(Search)
  

  