import React ,{ Component , Fragmnent} from 'react';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form,Image,Pagination} from 'react-bootstrap';
import './search.css';

import {Redirect , Link ,useHistory} from "react-router-dom";
import Searchimg from './../../assets/img/searchimg.jpg'; 
import { connect } from 'react-redux'
import Resource from "./partials/resource";
import { searchResourceAction ,}  from "./../../../actions/resourceActions";
import Searchbar from './partials/Searchbar/';

// get our fontawesome imports
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faHome , faDownload } from "@fortawesome/free-solid-svg-icons";


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
        paginationResults: 16,
        
       
      };
     
      this.handler = this.handler.bind(this);  
      var pages=[];  
    }

    componentDidMount() {

        if(this.props.location.state != undefined){
          const {keywords , type } = this.props.location.state;
          this.props.dispatch(searchResourceAction(type,keywords));
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
    }


  
    /**
     * {All pagination handling Functions  }
     */
    handleFirst = () => {
        this.setState({activePage : '1'})
    }

    handlePrevious = () => {
      let { activePage } = this.state;
      
      if(activePage !== 1 ){
          this.setState({activePage:activePage-1 })
      }
    }

    handleLast = () => {
      this.setState({activePage: this.state.pageCount })
    }

    handleNext = () => {
      let { pageCount,activePage } = this.state;
      if(pageCount == activePage){

      }else{
        this.setState({activePage:activePage+1 }) 
      }
    }

    handlePageChange = (i) => {
      this.setState({activePage:i})
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
             {/**
             * { filtering results belonging to activePage only}
             */}
            { resources !== undefined && resources.map((resource,i) => {
                
              if( (i < (paginationResults*activePage)) && (i >= (paginationResults*(activePage-1))) ){
                   return <Resource resource={resource} key={i}/>
              }   
            })}

           
           
          </Row>   
          { resources=='' && <Row><Col md={3}></Col><Col md={6}><h1>Sorry No Resource against keywords</h1></Col><Col md={3}></Col></Row>}
            
          <Row>
              <Col md={2}></Col>
              <Col md={8} className="paginationcustome">
                 
                  <Pagination>
                    <Pagination.First onClick={this.handleFirst}/>
                    <Pagination.Prev  onClick={this.handlePrevious} />  
                    {/**
                     * { dynamic page number generetation inside pagination }
                     */}
                    { pagess !== undefined && pagess.map((object,i) => {
                                              
                        return <Pagination.Item onClick={ () => this.handlePageChange(i)} active={activePage==i ? 'active' : null } key={i}>{i}</Pagination.Item>
                    })}
                    {/*<Pagination.Ellipsis />*/}
                    <Pagination.Next onClick={this.handleNext}/>
                    <Pagination.Last onClick={this.handleLast}/>
                  </Pagination>
                
              </Col>
              <Col md={2}></Col>  

          </Row>
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
  

  