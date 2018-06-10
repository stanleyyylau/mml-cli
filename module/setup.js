var path = require('path')
var fs = require('fs')
var shell = require('shelljs')
var util = require('./util.js')

var setup = {}

setup.writeConfigJsToPath = function (targetPath, obj) {
	var configTplPath = path.join(__dirname, '..', 'deps', 'themes', 'config.json.tpl')
	var configContent = fs.readFileSync(configTplPath, 'utf8');

	configContent = configContent.replace('${proxyUrl}', obj.proxyUrl)
	configContent = configContent.replace('${proxyPort}', obj.proxyPort)
	fs.writeFileSync(path.join(targetPath, 'config.json'), configContent);
}

setup.installBetheme = function(targetPath) {
	var shell = require('shelljs')
	var bethemePath = path.join(__dirname,'..', 'deps', 'themes', 'betheme')

	shell.cp('-R', bethemePath, targetPath);
}

setup.installMelement = function(targetPath, obj) {
	// Copy betheme child to target path
	var bethemeChildPath = path.join(__dirname,'..', 'deps', 'themes', 'betheme-child')
	shell.cp('-R', bethemeChildPath, targetPath);
	
	// Generate config.js to path
	setup.writeConfigJsToPath(bethemeChildPath, obj)
}

setup.installWordpress = function(targetPath, obj) {
	var config = {
		dbname : obj.dbname,
		url : obj.url,
		dbuser : obj.dbuser || 'root',
		dbpass : obj.dbpass || 'root',
		dbprefix : obj.dbprefix || 'wp_',
		title : obj.title || 'Mmldigital Marketing',
		adminUser : obj.adminUser || 'mmldigi',
		adminPass : obj.adminPass || 'mmldigi',
		adminEmail : obj.adminEmail || 'stanley@mmldigi.com'
	}
	var theCommandToInstallWordpress = `wp core download --allow-root && wp core config --dbname=${config.dbname} --dbuser=${config.dbuser} --dbpass=${config.dbpass} --dbprefix=${config.dbprefix} --allow-root && wp core install --url=${config.url} --title="${config.title}" --admin_user=${config.adminUser} --admin_password=${config.adminPass} --admin_email=${config.adminEmail} --allow-root`;
	
	// Run external tool synchronously
	if (shell.exec(theCommandToInstallWordpress).code !== 0) {
	  shell.echo('Error: install wordpress failed');
	  shell.exit(1);
	  return false
	}

	// Save this installation info to DB
	var db = util.readObjFromDb()
	db.siteData.push(config)
	util.writeObjToDb(db)
	return true;
}

module.exports = setup