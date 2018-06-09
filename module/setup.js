const path = require('path')
const fs = require('fs')
const shell = require('shelljs')
const util = require('./util.js')

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

	const bethemePath = path.join(__dirname,'..', 'deps', 'themes', 'betheme')
	shell.cp('-R', bethemePath, targetPath);
	console.log('betheme successful')
}

setup.installMelement = function(targetPath, obj) {
	// copy betheme child to tartet path
	const bethemeChildPath = path.join(__dirname,'..', 'deps', 'themes', 'betheme-child')
	shell.cp('-R', bethemeChildPath, targetPath);
	
	// generate config.js to path
	console.log(obj)
	setup.writeConfigJsToPath(bethemeChildPath, obj)
}

setup.installWordpress = function(targetPath, obj) {
	// go to that target path

	const config = {
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

	// wp core download && wp core config --dbname=dbtest --dbuser=root --dbpass=root --dbprefix=wp_ && wp core install --url=http://www.test.com --title="Welcome To My Site" --admin_user=mmldigi --admin_password=83dTc6kRz53ECIN --admin_email=stanley@mmldigi.com
	// Run external tool synchronously
	console.log('Installing Wordpress... please wait....')
	const theCommandToInstallWordpress = `wp core download && wp core config --dbname=${config.dbname} --dbuser=${config.dbuser} --dbpass=${config.dbpass} --dbprefix=${config.dbprefix} && wp core install --url=${config.url} --title="${config.title}" --admin_user=${config.adminUser} --admin_password=${config.adminPass} --admin_email=${config.adminEmail}`;
	console.log(theCommandToInstallWordpress)
	//return;
	if (shell.exec(theCommandToInstallWordpress).code !== 0) {
	  shell.echo('Error: install wordpress failed');
	  shell.exit(1);
	}

	// write this site to dabase
	var db = util.readObjFromDb()
	db.siteData.push(config)
	util.writeObjToDb(db)
	return true;

}

module.exports = setup