const initialState = {
    response:[],
    errors:[],
    resource: [],
    searchedResources:[],
    suggestedResources:[],
};
  
  export default function(state = initialState, action) {
    switch (action.type) {

      case "SEARCH_RESOURCE": 
          return {
            ...state,
            searchedResources: action.payload
          };
      
      case "SUGGEST_RESOURCE": 
          return {
            ...state,
            suggestedResources: action.payload.data
          };    
      
      case "GET_RESOURCE": 
          return {
            ...state,
            resource: action.payload.data
          };    
      
      case "RETURN_EMPTY":
          return {
              ...state,
              resource:null
          }
      
      case "ASSIGN_SITE_TASK": 
          return {
             ...state,
             errors: [],
          };   
     
      case "UPDATE_TASK_STATUS":
          return {
             ...state,
            errors: [],
          }; 
      
      case "FETCH_TASK_COMMENTS":
            return {
              ... state,
              task_comments: action.payload,
            }; 
      
      case "ADD_TASK_COMMENT":
        return {
          ...state,
          errors:[],
        };
      
      case "ERROR_OCCURED":
        return {
            ...state,
            errors: action.payload.errors,
            success: false
        };                    
      default:
        return state;
    }
  }  

