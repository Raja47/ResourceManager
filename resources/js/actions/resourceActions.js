import axios from "axios"


export const searchResourceAction =  (type, keywords , page_no , paginationResults) => dispatch => {
   
   axios.get(api_url+`/site/resource/search/${type}/${keywords}/${page_no}/${paginationResults}`)
  .then((response) => {
    
    if(response.data){
      dispatch({type: "SEARCH_RESOURCE", payload: response.data });
    }
    else{
      dispatch({type: "RETURN_EMPTY", payload: response.data});
    }
  })
  .catch( (error) => {
    console.log(error)
    dispatch({type: "ERROR_OCCURED", payload: error});
  } )

};



export const suggestResourceAction =  (type, keywords) =>  dispatch => {
   
  axios.get(api_url+`/site/resource/suggest/${type}/${keywords}`)
  .then((response) => {
    
    if(response.data){

      dispatch({type: "SUGGEST_RESOURCE", payload: response.data });
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



export const getResourceAction = (id) => dispatch => {
   
  axios.get(api_url+`/site/resource/${id}`)
  .then((response) => {
    
    if(response.data){

      dispatch({type: "GET_RESOURCE", payload: response.data });
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


export const downloadResourceAction = (type,id) => dispatch => {
   
  axios.get(api_url+`/site/download/${type}/${id}`)
  .then((response) => {
    
    if(response.data){

      dispatch({type: "GET_RESOURCE", payload: response.data });
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

