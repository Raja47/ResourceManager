import axios from "axios"





export const searchResourceAction = (type, keywords) => dispatch => {
   
  axios.get(api_url+`/site/resource/search/${type}/${keywords}`)
  .then((response) => {
    
    if(response.data){

      dispatch({type: "SEARCH_RESOURCE", payload: response.data });
    }
    else{

      dispatch({type: "RETURN_EMPTY", payload: response.data});
    }
  })
  .catch((error) => {
    console.log(error)
    dispatch({type: "ERROR_OCCURED", payload: error});
  })

};


