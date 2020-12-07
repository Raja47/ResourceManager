  
import { combineReducers } from 'redux'

import resourceReducer from './resources.js'
import loginReducer from './auth.js'
import emailReducer from './emails.js'
import { loadingBarReducer } from 'react-redux-loading-bar'


export default combineReducers({
 
  resourceReducer,
  loginReducer,
  emailReducer,
  loadingBar: loadingBarReducer,
})
