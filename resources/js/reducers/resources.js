



// const searchResourcesReducer = (state = initialState , action ) => {

//   switch (action.type) {
//     case "SEARCH_RESOURCE":return {
//         ...state ,
//         action
//       }
//     default:
//       return state
//   }
// }


const initialState = {
    response:[],
    errors:[],
    searchedResources:[],
    task_comments:[],
  };
  
  export default function(state = initialState, action) {
    switch (action.type) {
    

      case "SEARCH_RESOURCE": 
          return {
            ...state,
            resources: action.payload
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

   // const byId = (state = {}, action) => {
//   switch (action.type) {
//     case RECEIVE_PRODUCTS:
//       return {
//         ...state,
//         ...action.products.reduce((obj, product) => {
//           obj[product.id] = product
//           return obj
//         }, {})
//       }
//     default:
//       const { productId } = action
//       if (productId) {
//         return {
//           ...state,
//           [productId]: products(state[productId], action)
//         }
//       }
//       return state
//   }
// }

// const visibleIds = (state = [], action) => {
//   switch (action.type) {
//     case RECEIVE_PRODUCTS:
//       return action.products.map(product => product.id)
//     default:
//       return state
//   }
// }

// export default combineReducers({
//   byId,
//   visibleIds
// })

// export const getProduct = (state, id) =>
//   state.byId[id]

// export const getVisibleProducts = state =>
//   state.visibleIds.map(id => getProduct(state, id))