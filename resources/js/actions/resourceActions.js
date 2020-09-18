const resources = [
          {"id": 10, "title": "iPad", "price": 500.01, "inventory": 2},
          {"id": 20, "title": "H&M",  "price": 10.99, "inventory": 10},
          {"id": 30, "title": "Charli","price": 19.99, "inventory": 5}
        ];


// const receiveProducts = products => ({
//   type: "SEARCH_RESOURCE_SUCCESS",
//   resources
// })

// export const searchResourcesAction = () => dispatch => {
    
//     dispatch({type: "SEARCH_RESOURCE",
//   	payload:resources});
  
// };

export const searchResourcesAction = () => dispatch => {
   
  // axios.get(api_url+`/site-task/index/`)
  // .then((response) => {
    
  //   if(response.data.status){

    return dispatch({type: "SEARCH_RESOURCE", payload: resources});

  //   }
  //   else{
  //     dispatch({type: "FETCH_SITE_TASKS", payload: response.data});
  //   }
  // })
  // .catch((error) => {
  //   console.log(error)
  // //   dispatch({type: "FETCH_EMPLOYEES_REJECTED", payload: error});
  // })

};