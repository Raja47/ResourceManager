import React from 'react'
import { render } from 'react-dom'
import { createStore, applyMiddleware } from 'redux'
import { Provider } from 'react-redux'
import { createLogger } from 'redux-logger'
import thunk from 'redux-thunk'
import reducer from './../reducers/index.js'
// import { getAllProducts } from './actions'
import { loadingBarMiddleware } from 'react-redux-loading-bar'
import App from './App.jsx'

	const middleware = [ thunk ];
	if (process.env.NODE_ENV !== 'production') {
	  middleware.push(createLogger());
	  middleware.push(loadingBarMiddleware());
	}

	const store = createStore(
		reducer,
		/* preloadedState, */
		 applyMiddleware(...middleware) ,
	);

// store.dispatch(getAllProducts())
// window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__() &&

render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById('app')
)