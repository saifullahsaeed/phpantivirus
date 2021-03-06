[![Join the chat at https://gitter.im/phpMussel2/Lobby](https://badges.gitter.im/phpMussel2/Lobby.svg)](https://gitter.im/phpMussel2/Lobby)
[![v1: PHP >= 5.4.0](https://img.shields.io/badge/v1-PHP%20%3E%3D%205.4.0-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![v2~v3: PHP >= 7.2.0](https://img.shields.io/badge/v2%7Ev3-PHP%20%3E%3D%207.2.0-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-brightgreen.svg)](http://makeapullrequest.com)

## **What is phpMussel?**

An ideal solution for shared hosting environments, where it's often not possible to utilise or install conventional anti-virus protection solutions, phpMussel is a PHP script designed to **detect trojans, viruses, malware and other threats** within files uploaded to your system wherever the script is hooked, based on the signatures of [ClamAV](https://www.clamav.net/) and others.

---


### What's this repository for?

This provides automatic file upload scanning to your website.

```
composer require phpmussel/web
```

__*Example:*__
```PHP
<?php
// Path to vendor directory.
$Vendor = __DIR__ . DIRECTORY_SEPARATOR . 'vendor';

// Composer's autoloader.
require $Vendor . DIRECTORY_SEPARATOR . 'autoload.php';

$Loader = new \phpMussel\Core\Loader();
$Scanner = new \phpMussel\Core\Scanner($Loader);
$Web = new \phpMussel\Web\Web($Loader, $Scanner);
$Loader->Events->addHandler('sendMail', new \phpMussel\PHPMailer\Linker($Loader));

// Scans file uploads (execution terminates here if the scan finds anything).
$Web->scan();

// Fixes possible corrupted file upload names (Warning: modifies the content of $_FILES).
$Web->demojibakefier();

// Cleanup.
unset($Web, $Scanner, $Loader);

?><html>
    <form enctype="multipart/form-data" name="upload" action="" method="post">
      <div class="spanner">
        <input type="file" name="upload_test[]" value="" />
        <input type="submit" value="OK" />
      </div>
    </form>
</html>
```

*(And, after using that form to try uploading `ascii_standard_testfile.txt`, a benign sample provided for the sole purpose of testing phpMussel)???*

__*Screenshot:*__
![Screenshot](https://raw.githubusercontent.com/phpMussel/extras/master/screenshots/web-v3.0.0-alpha2.png)

---


### Documentation:
- **[English](https://github.com/phpMussel/Docs/blob/master/readme.en.md)**
- **[??????????????](https://github.com/phpMussel/Docs/blob/master/readme.ar.md)**
- **[Deutsch](https://github.com/phpMussel/Docs/blob/master/readme.de.md)**
- **[Espa??ol](https://github.com/phpMussel/Docs/blob/master/readme.es.md)**
- **[Fran??ais](https://github.com/phpMussel/Docs/blob/master/readme.fr.md)**
- **[Bahasa Indonesia](https://github.com/phpMussel/Docs/blob/master/readme.id.md)**
- **[Italiano](https://github.com/phpMussel/Docs/blob/master/readme.it.md)**
- **[?????????](https://github.com/phpMussel/Docs/blob/master/readme.ja.md)**
- **[?????????](https://github.com/phpMussel/Docs/blob/master/readme.ko.md)**
- **[Nederlandse](https://github.com/phpMussel/Docs/blob/master/readme.nl.md)**
- **[Portugu??s](https://github.com/phpMussel/Docs/blob/master/readme.pt.md)**
- **[??????????????](https://github.com/phpMussel/Docs/blob/master/readme.ru.md)**
- **[????????](https://github.com/phpMussel/Docs/blob/master/readme.ur.md)**
- **[Ti???ng Vi???t](https://github.com/phpMussel/Docs/blob/master/readme.vi.md)**
- **[??????????????????](https://github.com/phpMussel/Docs/blob/master/readme.zh.md)**
- **[??????????????????](https://github.com/phpMussel/Docs/blob/master/readme.zh-tw.md)**

#### See also:
- [**phpMussel/phpMussel**](https://github.com/phpMussel/phpMussel) ??? The main phpMussel repository (you can get phpMussel versions prior to v3 from here).
- [**phpMussel/Core**](https://github.com/phpMussel/Core) ??? phpMussel core (dedicated Composer version).
- [**phpMussel/CLI**](https://github.com/phpMussel/CLI) ??? phpMussel CLI-mode (dedicated Composer version).
- [**phpMussel/FrontEnd**](https://github.com/phpMussel/FrontEnd) ??? phpMussel front-end (dedicated Composer version).
- [**phpMussel/Web**](https://github.com/phpMussel/Web) ??? phpMussel upload handler (dedicated Composer version).
- [**phpMussel/Examples**](https://github.com/phpMussel/Examples) ??? Prebuilt examples for phpMussel (useful for users which don't want to use Composer to install phpMussel).
- [**phpMussel/plugin-boilerplates**](https://github.com/phpMussel/plugin-boilerplates) ??? This repository contains boilerplate code which can be used to create new plugins for phpMussel.
- [**phpMussel/Plugin-PHPMailer**](https://github.com/phpMussel/Plugin-PHPMailer) ??? Provides 2FA and email notifications support for phpMussel v3+.
- [**CONTRIBUTING.md**](https://github.com/phpMussel/.github/blob/master/CONTRIBUTING.md) ??? Contribution guidelines.

---


### Current major version development status:

???Stage reached??? ???Major version??? | v0 | v1 | v2~v3
:--|:-:|:-:|:-:
Pre-Alpha<em><br />- Exploring early concepts/ideas. No code written/available yet.</em> | ??? | ??? | ???
Alpha<em><br />- Branched, but unstable. Not production-ready (high risk if used).</em> | ??? | ??? | ???
Beta<em><br />- Branched, but unstable. Not production-ready (low risk if used).</em> | ??? | ??? | ???
Stable<em><br />- First production-ready version has been tagged/released.</em> | ??? | ??? | ???
Mature<em><br />- Multiple stable versions/releases exist.</em> | ??? | ??? | ???
Locked<em><br />- Still maintained, but new features won't be implemented anymore.</em> | ??? | ???
EoL/Dead<em><br />- Not maintained anymore. If possible, stop using, and update ASAP.</em> | ???

---


Last Updated: 12 December 2020 (2020.12.12).
