  
import { combineReducers } from 'redux'

import resourceReducer from './resources.js'
import loginReducer from './auth.js'

export default combineReducers({
 
  resourceReducer,
  loginReducer,
})
