import React from 'react';
// import axios from 'axios';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form} from 'react-bootstrap';

import { connect } from 'react-redux';
import {sendFeedbackEmailAction,} from '../../../actions/emailActions.js';
import './feedback.css';


class Feedback extends React.Component {
  
  constructor(props) {
      super(props);
      this.state = { 
          email: '' ,
          message:''
          
        }
  }

  componentDidUpdate(prevProps) {
        
        if (this.props.success !== prevProps.success && this.props.success == true) {
          this.setState({email:'', message:''});    
          alert('Thankyou For Your Feedback');
        }else if(this.props.success !== prevProps.success && this.props.success == false){
             alert('Sorry some thing went wrong');
        }     
        
  }

  handleFormSubmit = () => {
    const email = this.state.email;
    const message = this.state.message;
   
    this.props.dispatch(sendFeedbackEmailAction(email,message));
  };
  
  
  
    render() {
        
      return (
        <Form action ="#" >
          <Row className="signupRow">
            <Col md={4}></Col>
              <Col md={4} className="signup">
                <h2><b>Send Your Feedback</b></h2>
                
                    <br/>
                    <Row className="forms">
                        <Col md={12}>
                        
                            <Form.Control 
                                type="email" 
                                placeholder="Enter Your Email" 
                                id="email" 
                                name="Email"
                                value={this.state.email}
                                onChange={e => this.setState({ email: e.target.value })}
                            />
                            
                            <br/>
                            
                            <Form.Group controlId="exampleForm.ControlTextarea1">
                                <Form.Control as="textarea" 
                                rows={3}
                                placeholder="Enter Your Feedback"
                                value={this.state.message}
                                onChange={e => this.setState({ message: e.target.value })}
                                />
                            </Form.Group>
                        
                        </Col>
                        
                        <br/>
                        
                        <Col md={12}>
                            <Button className="getbtn" variant="warning"  value="Submit" onClick={this.handleFormSubmit }>Send Now</Button>
                        </Col>
                    </Row>
                    
                  </Col>
                  <Col md={4} ></Col>
                </Row>
                <div>
                  
                </div>
          </Form >
      );
    }
  }
  

 function mapStateToProps(state){
   return {  
        
        success: state.emailReducer.success,
    }
 }

export default connect(mapStateToProps)(Feedback)
  