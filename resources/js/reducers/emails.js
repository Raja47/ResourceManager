const initialState = {
    errors:[],
    
};
  
  export default function(state = initialState, action) {
    switch (action.type) {

      case "SEND_EMAIL": 
          return {
            ...state,
            success:true
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

