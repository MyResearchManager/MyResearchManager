import gtk
import pycurl
import cStringIO
import md5


class PreferencesDialog(gtk.Window):
    def on_cancel_clicked(self,widget,window):
            window.destroy()

    def on_apply_clicked(self,widget,avatar,url,userCode):
        if userCode.get_text() == "":
            MessageDialog("The user code has to be filled").run()
        else:
            from os.path import expanduser
            home = expanduser("~")
            buf = cStringIO.StringIO()
 
            c = pycurl.Curl()
            c.setopt(c.URL, url.get_text()+'/user_email.php?usercode='+userCode.get_text())
            c.setopt(c.WRITEFUNCTION, buf.write)
            c.perform()
 
            mail = buf.getvalue()
            buf.close()
            print mail
            m = md5.new(mail).hexdigest()
            print m

            c2 = pycurl.Curl()
            fp = open(home+"/.avatar", "wb")
            c2 = pycurl.Curl()
            c2.setopt(pycurl.URL, 'http://www.gravatar.com/avatar/'+m)
            c2.setopt(pycurl.WRITEDATA, fp)
            c2.perform()
            c2.close()
            fp.close()

            avatar.set_from_file(home+"/.avatar")
            print home

    def on_dir_clicked(self,widget,location_entry):
        dir_selection = gtk.FileChooserDialog("Directory choice", self, gtk.FILE_CHOOSER_ACTION_SELECT_FOLDER)
        dir_selection.add_button("Cancel",gtk.RESPONSE_CANCEL)
        dir_selection.add_button("Ok",gtk.RESPONSE_ACCEPT) 
        response = dir_selection.run()

        if response == gtk.RESPONSE_ACCEPT:
            directory = dir_selection.get_filename()
            location_entry.set_text(directory)

        dir_selection.destroy()
        

    def __init__(self):
        preference_window = gtk.Dialog()
        preference_window.set_destroy_with_parent (True)
        preference_window.set_size_request(490, 200)
        preference_window.set_resizable(False)
        preference_window.set_position(gtk.WIN_POS_CENTER)
        preference_window.set_icon_from_file("myrm.ico");
        cancel_button = preference_window.add_button(gtk.STOCK_CANCEL,gtk.RESPONSE_CANCEL)
        apply_button = preference_window.add_button(gtk.STOCK_APPLY,gtk.RESPONSE_ACCEPT) 

        from os.path import expanduser
        home = expanduser("~")

        avatar = gtk.Image()
        avatar.set_from_file(home+"/.avatar")

        workspace = preference_window.get_content_area()

        #table to align objects
        table = gtk.Table(7,8,True)
            
        website_label = gtk.Label("<b>Url</b>")
        website_label.set_use_markup(True)
        website_entry = gtk.Entry()

        code_label = gtk.Label("<b>User code</b>")
        code_label.set_use_markup(True)
        code_entry = gtk.Entry()  

        location_label = gtk.Label("<b>Default dir</b>")
        location_label.set_use_markup(True)
        location_entry = gtk.Entry()
        location_entry.set_editable(False)
        
        dir_button = gtk.Button(label="...")
        dir_button.connect("clicked", self.on_dir_clicked,location_entry)

        time_label = gtk.Label("<b>Refresh time</b>")
        time_label.set_use_markup(True)
        time_button = gtk.SpinButton()
        time_button.set_range(1,30)
        time_button.set_numeric(True)
        time_button.set_increments(1,0)
        time_button.set_editable(False)
        time_button.set_value(30)
        time_min_label = gtk.Label("minutes")


        #buttons actions
        cancel_button.connect("clicked",self.on_cancel_clicked,preference_window)
        apply_button.connect("clicked",self.on_apply_clicked,avatar,website_entry,code_entry)

        #add avatar image
        table.attach(avatar,6,7,0,3)
                    
        #website label and entry
        table.attach(website_label,0,2,1,2)
        table.attach(website_entry,2,6,1,2)

        #code label and entry
        table.attach(code_label,0,2,2,3)         
        table.attach(code_entry,2,6,2,3)

        #local label, entry and selection
        table.attach(location_label,0,2,3,4)
        table.attach(location_entry,2,6,3,4)        
        table.attach(dir_button, 6, 7, 3,4)
    
        #time label and button
        table.attach(time_label,0,2,4,5)
        table.attach(time_button,2,3,4,5)
        table.attach(time_min_label,3,4,4,5)


        workspace.pack_start(table)                

        #preference_window.add(table)
        preference_window.set_title("MyResearchManager Preferences")
        preference_window.show_all()
        preference_window.run()

        

    
class SystrayIconApp:
    def __init__(self):
        self.tray = gtk.StatusIcon()
        self.tray.set_from_file("myrm.ico") 
        self.tray.connect('popup-menu', self.on_right_click)
        self.tray.set_tooltip(('MyResearchManager'))
        

    def on_right_click(self, icon, event_button, event_time):
        self.make_menu(event_button, event_time)

    def make_menu(self, event_button, event_time):
        menu = gtk.Menu()

        # show preferences dialog
        preferences = gtk.ImageMenuItem(gtk.STOCK_PREFERENCES,"Preferences")
        preferences.show()
        menu.append(preferences)
        preferences.connect('activate', self.show_preferences)

        # show about dialog
        about = gtk.ImageMenuItem(gtk.STOCK_ABOUT,"About")
        about.show()
        menu.append(about)
        about.connect('activate', self.show_about_dialog)

        # add quit item
        quit = gtk.ImageMenuItem(gtk.STOCK_QUIT,"Quit")
        quit.show()
        menu.append(quit)
        quit.connect('activate', gtk.main_quit)

        menu.popup(None, None, gtk.status_icon_position_menu,
                   event_button, event_time, self.tray)

    def show_about_dialog(self, widget):
        about_dialog = gtk.AboutDialog()
        about_dialog.set_destroy_with_parent (True)
        about_dialog.set_icon_name ("MyResearchManager")
        about_dialog.set_name('MyResearchManager')
        about_dialog.set_version('0.2')
        about_dialog.set_copyright("(C) 2013 MyResearchManager")
        about_dialog.set_comments(("MyResearchManager sync"))
        about_dialog.set_authors(['Igor Machado <igor.machado@gmail.com>','Pablo Munhoz <pablo.munhoz@gmail.com>'])
        about_dialog.run()
        about_dialog.destroy()
    
    def show_preferences(self, widget):
        # window definition
        PreferencesDialog()
   

if __name__ == "__main__":
    SystrayIconApp()
    gtk.main()
