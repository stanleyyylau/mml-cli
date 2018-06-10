#!/usr/bin/env node

var program = require('commander');
var prompt = require('prompt');
var setup = require('./module/setup.js')
var plugins = require('./module/plugins.js')
var util = require('./module/util.js')


function initProject() {
	if (util.checkExistOfWpConfigFile()) return console.log('Wordpress installed already...')
	var schema = {
		properties: {
		  dbname: {
		    message: 'Please enter database name, you must create database with lnmp first',
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

		 // Install Wordpress
	   var currentWorkingDir = process.cwd()
	   var configObj = Object.assign({}, result, { adminUser: 'mmldigi', adminPass: util.generateRanomPasword() }); 
		 var isWordpressInstalledSuccess = setup.installWordpress(currentWorkingDir, configObj)
		 if (isWordpressInstalledSuccess === false) return;

		 // Install themes
	   setup.installBetheme(util.getThemePath())
	   setup.installMelement(util.getThemePath(), {
	   	proxyUrl: configObj.url,
	   	proxyPort: util.readObjFromDb().currentProxyPort
	   })

	   // Update database port
	   var db = util.readObjFromDb()
	   db.currentProxyPort++
		 util.writeObjToDb(db)
		 
		 // Report install information
		 util.report(configObj)
	 });

}

function installPlugin() {
	if (util.checkExistOfWpConfigFile() == false) return console.log('Can\'t detect Wordpress in current directory')
	var allPlugins = plugins.viewAllPlugs()
	console.log('Please select a plugin you want to install')
	for(key in allPlugins) {
		console.log(`${key}: ${allPlugins[key]}`)
	}
	console.log("q: Exit the program")

	var schema = {
		properties: {
		  plugin: {
		    message: 'Enter the number',
				default: 'q', 
		  }
		}
	}

	prompt.start();
	prompt.get(schema, function (err, result) {
		if(result.plugin != 'q' && typeof(allPlugins[result.plugin]) != 'undefined') {
			plugins.installPlugin(allPlugins[result.plugin])
		}
	})
}

program
	.version('0.0.1')
	.usage('[Command]');

program
	.command('init')
	.description('Initialise a new wordpress project')
  .action(function(env, options){
    initProject()
  });

program
  .command('plugin')
  .description('Select a wordpress plugin and install')
  .action(function(cmd, options){
    installPlugin()
  });

program.parse(process.argv);

if(process.argv.length == 2) program.help()