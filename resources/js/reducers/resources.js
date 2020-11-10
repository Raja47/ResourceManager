const initialState = {
    response:[],
    errors:[],
    resource: [],
    searchedResources:[],
    searchedFor:[],
    totalResults:'',
    page_no:'',
    suggestedResources:[],
    suggestedKeywords:[],
};
  
  export default function(state = initialState, action) {
    switch (action.type) {

      case "SEARCH_RESOURCE": 
          return {
            ...state,
            searchedResources: action.payload.data,
            searchedFor : action.payload.searchedFor,
            totalResults : action.payload.totalResults,
            page_no: action.payload.page_no,
          };
      
      case "SUGGEST_RESOURCE": 
          return {
            ...state,
            suggestedResources: action.payload.data,
            suggestedKeywords : action.payload.suggestedKeywords
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

