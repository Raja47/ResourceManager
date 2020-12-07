import axios from "axios"


export const sendFeedbackEmailAction =  (email, message ) => dispatch => {
   
   axios.post(api_url+`/site/feedback/email`,{email:email , message:message}) 
  .then((response) => {
    
    if(response.data){
      dispatch({type: "SEND_EMAIL", payload: response.data });
    }
    
  })
  .catch( (error) => {
    console.log(error)
    dispatch({type: "ERROR_OCCURED", payload: error});
  } )

};









