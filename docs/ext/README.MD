# OpenGears Framework
**Documentation**

### Extensions

#### Information
OpenGears can be extended by using extensions.

Extensions are presented in as **.php** files from **system/extensions** directory.

#### Enabling extensions
To load extension, you have to specify it in **index.php** bootstrap file.

```
Extensions::load(
    array('extension1','extension2')
);
```

#### Require an extension
To check if extension is enabled, use **Extensions::request**
```
// Will throw ExtensionNotFoundException, if ajaxResponse module not loaded
Extensions::request("ajaxResponse");
```



