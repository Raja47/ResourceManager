  
import { combineReducers } from 'redux'

import searchResourcesReducer from './resources.js'
import loginReducer from './auth.js'

export default combineReducers({
 
  searchResourcesReducer,
  loginReducer,
})
