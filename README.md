# Moodle CustomField Official Activity
# How to use (Guide in 4.3)

**Install this plugin and make sure to enable it in Administrator > Plugins > Manage custom field types**  
![Screenshot from 2023-12-18 14-50-35](https://github.com/duyphamluanh/customfield_officialactivity/assets/32034702/b7a79113-5178-4512-9fe1-29a44f0be00f)

**Install plugin:** ![Moodle Local ModCustomField](https://github.com/duyphamluanh/moodle-local_modcustomfields)
+ **Settings ModCustomField:**
  
![Screenshot from 2023-12-18 14-48-54](https://github.com/duyphamluanh/customfield_officialactivity/assets/32034702/7c2fc548-2a3b-44a5-a6e4-dfeb5bd5932d)
  
+ **Add New Category**  
   
![Screenshot from 2023-12-18 14-51-15](https://github.com/duyphamluanh/customfield_officialactivity/assets/32034702/5732ac1f-0d67-4509-8044-a1d47da10bdc)

+ **Add a new custom field -> Choose Official Activity**  
Set name and short name => Save Changes
    
![Screenshot from 2023-12-18 14-52-51](https://github.com/duyphamluanh/customfield_officialactivity/assets/32034702/0d0c3405-34bd-41ba-afce-203b46e02e6c)

**In module:**  
**Add the idnumber in Common module settings for the Official module:**  
  
![Screenshot from 2023-12-18 14-55-05](https://github.com/duyphamluanh/customfield_officialactivity/assets/32034702/094c7b4c-4d65-4364-b7fd-59362d048546)

**The official module options will be shown if they have an idnumber**  
  
![Screenshot from 2023-12-18 14-55-31](https://github.com/duyphamluanh/customfield_officialactivity/assets/32034702/7ef94233-ac07-43fb-bdb0-41c92dabfb44)

# How it works?
In the `course/modedit.php` file, you will find the following line of code at the end of the `standard_coursemodule_elements` function:
```php
$this->plugin_extend_coursemodule_standard_elements();
```
The plugin_extend_coursemodule_standard_elements function is defined as follows:
```
protected function plugin_extend_coursemodule_standard_elements() {
    $callbacks = get_plugins_with_function('coursemodule_standard_elements', 'lib.php');
    foreach ($callbacks as $type => $plugins) {
        foreach ($plugins as $plugin => $pluginfunction) {
            // We have exposed all the important properties with public getters - and the callback can manipulate the mform
            // directly.
            $pluginfunction($this, $this->_form);
        }
    }
}
```
This function calls all the functions that end with 'coursemodule_standard_elements'.  
The plugin `modcustomfields` has a function named `local_modcustomfields_coursemodule_standard_elements` that matches this pattern. Consequently, the form will be rendered at the end of the module settings form.

In this plugin, We use the `idnumber` field to save information as an official activity. Therefore, if we change the `idnumber` of an official activity, we should also update the settings in the corresponding expired activity.

Since we rely on the `idnumber`, even if the ID of an activity or the ID of the activity's course module changes during the restoration process, the settings will remain the same.

# Get data with course module id  
Using static method: `get_officialactivity_by_cmid($cmid, $fieldshortname = 'official_acitvity')`  
Data return (sample):  
```
{
    instanceid: "3083"
    contextid: "3214"
    value: "KTC1"
}
```  
*instanceid is $cmid  