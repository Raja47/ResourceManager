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
        countResults:0,
        paginationResults: 60,
        loading:false,
    };
     
      this.handler = this.handler.bind(this);  
      let pages=[];  
     
    }
    
    componentDidMount() {
        if(this.props.location.state != undefined){
          const {keywords , type } = this.props.location.state;
          const {activePage,paginationResults} = this.state;
          this.props.dispatch(searchResourceAction(type,keywords,activePage,paginationResults));
          this.setState({loading:true,keywords:keywords,type:type });
        }
    }

    componentDidUpdate(prevProps) {
        
        if (this.props.resources !== prevProps.resources) {
            
            if(this.props.totalResults != undefined){ 
              var pages = [];
              var results = Math.ceil(this.props.totalResults/this.state.paginationResults);
              for(var i = 1; i <= results ; i++) {
                  pages[i] = i;
              }
              this.pages = pages;  
              this.setState({resources : this.props.resources , activePage : this.props.activePage ,countResults:this.props.totalResults,pageCount:Math.ceil(this.props.totalResults/this.state.paginationResults)});
              
            }else{
              this.setState({ resources: this.props.resources});
            }
            this.props.dispatch(hideLoading());
            this.setState({loading:false})
            
        }
          
        if(this.props.location.state !== prevProps.location.state ){
             this.handler(this.props.location.state.type,this.props.location.state.keywords);
        }
    }

    handler = (type ,keywords) => {
        const { paginationResults } = this.state;
        const activePage=1;
        this.props.dispatch(searchResourceAction(type,keywords,activePage,paginationResults));
        this.props.dispatch(showLoading());
        this.setState({keywords:keywords,type:type});
    }

    /**
     * {All pagination handling Functions  }
    */
    handleFirst = () => {
        const activePage=1;
        const {type,keywords,paginationResults} = this.state;
        this.props.dispatch(searchResourceAction(type,keywords,activePage,paginationResults));
        this.props.dispatch(showLoading());
        setTimeout( () =>{this.props.dispatch(hideLoading()) }, 1500);
    }

    handlePrevious = () => {
      let {type,keywords,activePage,paginationResults} = this.state;
      
      if(activePage !== 1 ){
          activePage = activePage-1;
          this.props.dispatch(searchResourceAction(type,keywords,activePage,paginationResults));
          this.props.dispatch(showLoading());
          setTimeout( () =>{this.props.dispatch(hideLoading()) }, 1500);
      }
    }

    handleLast = () => {
      let {type,keywords,paginationResults,pageCount} = this.state;
       this.props.dispatch(searchResourceAction(type,keywords,pageCount,paginationResults));
       this.props.dispatch(showLoading());
      setTimeout( () =>{ this.props.dispatch(hideLoading())} , 1500);
    }

    handleNext = () => {
      let {type,keywords,activePage,paginationResults,pageCount} = this.setState;
      if(pageCount == activePage){

      }else{
        activePage=activePage+1;
       
        this.props.dispatch(searchResourceAction(type,keywords,activePage,paginationResults));
        this.props.dispatch(showLoading());
        setTimeout( () =>{this.props.dispatch(hideLoading())} , 1500);
      }
    }

    handlePageChange = (i) => {
      let {type,keywords,activePage,paginationResults,pageCount} = this.state;
      if(activePage != i ){
          activePage=i;
          this.props.dispatch(searchResourceAction(type,keywords,activePage,paginationResults));
      }
      this.props.dispatch(showLoading());
      setTimeout( () =>{this.props.dispatch(hideLoading())} , 1500);
    }
    

  /**
   * Renders the Searchbar & Resource Search Results{resources} 
   *
   * @return { renders Page Searchbar & Resources(All Type in Resource Comp) }
   */
  render () {
   
    const {resources,activePage,pageCount,countResults,paginationResults} = this.state;
    
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
            { resources !== undefined  && Array.isArray(resources) && resources.map((resource,i) => {
                
              
                   return <Resource resource={resource} key={i} position={i}/>
             
            })}
            
            { resources !== undefined  && !Array.isArray(resources) && Object.values(resources).map((resource , i) => {
                
              
                   return <Resource resource={resource} key={i} position={i}/>
             
            })}
            
           
           
          </Row>
          { resources == undefined && this.state.loading==false && <Row><Col md={3}></Col><Col lg={6} className="errormessage"><h1>Please enter keyword to search.</h1> <p>No Keywords</p></Col><Col md={3}></Col></Row> }
          { resources=='' && <Row><Col md={3}></Col><Col lg={6} className="errormessage"><h1>Sorry No Resource against keywords</h1> <p>404</p></Col><Col md={3}></Col></Row>}
          { resources != ''  && resources != undefined && resources != [] && <Row>
              
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
                        if( (i > (parseInt(activePage)-5) &&  i < (parseInt(activePage)+5))){
                            return <Pagination.Item onClick={ () => this.handlePageChange(i)} active={activePage==i ? 'active' : null } key={i}>{i}</Pagination.Item> 
                        }                
                    })}
                    <Pagination.Next onClick={this.handleNext}/>
                    <Pagination.Last onClick={this.handleLast}>Last({pageCount})</Pagination.Last><span className="">{" (Records:"+countResults +")" }</span>
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
        activePage: state.resourceReducer.page_no,
        totalResults: state.resourceReducer.totalResults,
    }
 }

export default connect(mapStateToProps)(Search)
  

  