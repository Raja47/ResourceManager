import axios from "axios"





export const searchResourcesAction = () => dispatch => {
   
  axios.get(api_url+`/site/resource`)
  .then((response) => {
    
    if(response.data){

      dispatch({type: "SEARCH_RESOURCE", payload: response.data });

    }
    else{
      dispatch({type: "FETCH_SITE_TASKS", payload: response.data});
    }
  })
  .catch((error) => {
    console.log(error)
  //   dispatch({type: "FETCH_EMPLOYEES_REJECTED", payload: error});
  })

};