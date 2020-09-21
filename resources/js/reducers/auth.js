 const initialState = {
   authenticatedUser:[]
};

 export default function(state = initialState, action) {
    switch (action.type) {
    

      case "ATTEMPT_LOGIN": 
          return {
            ...state,
            authenticatedUser: action.payload
          };
      case "FETCH_AUTH_USER_TASKS": 
          return {
            ...state,
            site_tasks: action.payload.data
          };    
      case "VALIDATE_SITE_TASK":
          return {
              ...state,
              errors: action.payload.errors,
              success: false
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
       case "VALIDATE_TASK_COMMENT":
        return {
            ...state,
            errors: action.payload.errors,
            success: false
        };                    
      default:
        return state;
    }
  }  