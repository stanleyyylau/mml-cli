var path = require('path')
var fs = require('fs')
var shell = require('shelljs')

var plugins = {}

plugins.viewAllPlugs = function(){
	var pluginDir = path.join(__dirname,'..', 'deps', 'plugins')
	var allPluginSlug = fs.readdirSync(pluginDir) 
	var allPlugin = {}
	
	allPluginSlug.forEach(function(slug, index){
		if(slug == '.DS_Store') return;	
		allPlugin[index] = slug
	})
	return allPlugin;
}


plugins.installPlugin = function(pluginSlug){

	var currentWorkingDir = process.cwd()
	var pluginPath = path.join(__dirname, '..', 'deps', 'plugins', pluginSlug)
	var installPath = path.join(currentWorkingDir, 'wp-content', 'plugins')

	if ( fs.existsSync(path.join(installPath, pluginSlug)) ){
		return console.log(`Plugin you want to install exist in this Wordpress installation already...`)
	}

	shell.cp('-R', pluginPath, installPath);
	console.log('Plugin installed successful, please go to Wordpress backend to activate it')
}

module.exports = plugins