const path = require('path')
const { readdirSync, statSync } = require('fs')
const shell = require('shelljs')

var plugins = {}

plugins.viewAllPlugs = function(){
	var pluginDir = path.join(__dirname,'..', 'deps', 'plugins')
	
	var allPluginSlug = readdirSync(pluginDir) // this includes .DS_Store

	//Output plugin names line by line
	allPluginSlug.forEach(function(slug){
		if(slug == '.DS_Store') return;
		let pluginName = slug.replace(/-/g, ' ')
		console.log(pluginName)
	})
}


//plugins.installPlugin('/Users/stanley/Desktop/mml-cli/plugins/contact-form-7','/Users/stanley/Documents/wwwroot/karomy.mml.local/wp-content/plugins')
plugins.installPlugin = function(pluginPath, installPath){
	// if folder exsit in install Path, warning and return
	const pluginSlug = path.basename(pluginPath)
	if ( fs.existsSync(path.join(installPath, pluginSlug)) ){
		return console.log(`Plugin you want to install exsit in this Wordpress installation already...`)
	}

	// if not, do the copy and console.log
	shell.cp('-R', pluginPath, installPath);
	console.log('Plugin installed successful, please go to Wordpress backend to activate it')
}

module.exports = plugins