import React from 'react';
// import axios from 'axios';
import {Button, Carousel ,Container ,Row,Col,Card,Tabs,Tab,Sonnet ,Form} from 'react-bootstrap';
import './feedback.css';


class Feedback extends React.Component {
//   constructor(props) {
//     super(props);
//     this.state = {
//       fname: '',
//       email: '',
//       pass: '',
//       conpass: '',
//     }
//   }

  

//   handleFormSubmit = e => {
//     e.preventDefault();
    

//     // perform all neccassary validations
//     const { pass, conpass } = this.state;
//     if (pass !== conpass) {
//       alert("Passwords don't match");
//     } else {
//         axios.post('http://localhost/api.php',this.state)

//         .then(function (response) {
//           console.log(response.data);
//         })
//         .catch(function (error) {
//           console.log(error);
//         });
//     }

//   };
  
  
  
    render() {
      return (
        <form action ="#" >
          <Row className="signupRow">
            <Col md={4}></Col>
              <Col md={4} className="signup">
                <h2><b>Send Your Feedback</b></h2>
                
                    <br/>
                    <Row className="forms">
                        <Col md={12}>
                         <Form.Control type="email" placeholder="Enter Your Email" 
                        id="email" name="Email"
                    //     value={this.state.email}
                    //   onChange={e => this.setState({ email: e.target.value })}
                        /><br/>
                        <Form.Group controlId="exampleForm.ControlTextarea1">
                            <Form.Control as="textarea" rows={3} placeholder="Enter Your Feedback" />
                          </Form.Group>
                        </Col>

                        <br/>
                    
                        <Col md={12}>
                        <Button className="getbtn" variant="warning"  value="Submit">Send Now</Button>
                        </Col>
                    </Row>
                    
                  </Col>
                  <Col md={4} ></Col>
                </Row>
                <div>
                  
                </div>
          </form >
      );
    }
  }
  

  export default Feedback;
  