#!/usr/bin/env node

const program = require('commander');
const prompt = require('prompt');
const setup = require('./module/setup.js')
const plugins = require('./module/plugins.js')
const util = require('./module/util.js')



// setup.installWordpress('path', {
// 	dbname: 'test',
// 	url: 'tst.com',
// 	adminPass : util.generateRanomPasword()
// })




function initProject() {
	if (util.checkExistOfWpConfigFile()) return console.log('Wordpress installed already...')
	var schema = {
		properties: {
		  dbname: {
		    message: 'Please enter database name, you must create databse with lnmp first',
		    required: true
		  },
		  url: {
		  	pattern: /http:\/\/[a-z-]+\.mml\.local$/,  
		    message: 'Please enter URL for your Wordpress site, eg: http://project.mml.local',
		    required: true
		  },
		  dbuser: {
		    message: 'Database user, default root',
		    default: 'root', 
		  },
		  dbpass: {
		    message: 'Database password, default root',
		    default: 'root', 
		  },
		  dbprefix: {
		    message: 'database prefix, default wp_',
		    default: 'wp_', 
		  },
		  title: {
		    message: 'Wordpress title, default \'Mml digital marketing\'',
		    default: 'Mml digital marketing', 
		  },
		  adminEmail: {
		    message: 'Wordpress email address, default \'stanley@mmldigi.com\'',
		    default: 'stanley@mmldigi.com', 
		  },
		}
	}
	prompt.start();
	prompt.get(schema, function (err, result) {
	    
	    //Log the results.
	   const currentWorkingDir = process.cwd()
	   const configObj = Object.assign({}, result, { adminUser: 'mmldigi', adminPass: util.generateRanomPasword() }); 
	   
	   const isWordpressInstalledSuccess = setup.installWordpress(currentWorkingDir, configObj)
	   console.log('Wodrpess install successfully, now install betheme')
	   setup.installBetheme(util.getThemePath())
	   setup.installMelement(util.getThemePath(), {
	   	proxyUrl: configObj.url,
	   	proxyPort: util.readObjFromDb().currentProxyPort
	   })

	   console.log(`port...${util.readObjFromDb().currentProxyPort}`)

	   //update database port
	   const db = util.readObjFromDb()
	   db.currentProxyPort++
	   util.writeObjToDb(db)
	 });

}

initProject()