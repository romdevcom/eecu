(window.webpackJsonp=window.webpackJsonp||[]).push([[33],{562:function(e,t,r){var content=r(581);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(16).default)("031eaef7",content,!0,{sourceMap:!1})},577:function(e,t,r){var content=r(585);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(16).default)("50887592",content,!0,{sourceMap:!1})},580:function(e,t,r){"use strict";r(562)},581:function(e,t,r){var o=r(15)(!1);o.push([e.i,".profileForm[data-v-7924df9c]{position:relative}.debug[data-v-7924df9c]{position:absolute;right:0;top:-50px}",""]),e.exports=o},582:function(e,t,r){"use strict";r.r(t);r(25),r(65),r(168),r(303),r(84);var o={name:"ProfileForm",props:{data:{type:Object,required:!0},avatarUploadApi:{type:String,required:!0}},data:function(){var e=this;return{fileRecords:[],fileRecordsForUpload:[],formValid:!1,nameRules:[function(t){return!!t||e.$store.state.gui.errors.formRequire},function(e){return"string"==typeof e&&e.length<=25||"Поле повинно мати, менше ніж 25 символів"},function(e){return"string"==typeof e&&e.length>=3||"Поле повинно мати, більше ніж 3 символи"},function(t){return!/\d/.test(t)||e.$store.state.gui.errors.formValid}],emailRules:[function(t){return!!t||e.$store.state.gui.errors.formRequire},function(t){return/^\w+.?\w*@[a-zA-Z_]+?\.(?!ru$)[a-zA-Z]{2,}(\.(?!ru$)[a-zA-Z]{2,})?$/.test(t)||e.$store.state.gui.errors.formEmail}],phoneRules:[function(t){return!!t||e.$store.state.gui.errors.formRequire},function(t){return null!==t&&10===t.replace(/\D/g,"").length||e.$store.state.gui.errors.formPhone}],geoRules:[function(t){return!!t||e.$store.state.gui.errors.formRequire},function(t){return"string"==typeof t&&t.length>=20||e.$store.state.gui.errors.formValid}]}},computed:{getXSRF:function(){var e=document.cookie.match(new RegExp("(?:^|; )"+"XSRF-TOKEN".replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return e?{"X-XSRF-TOKEN":decodeURIComponent(e[1])}:{"X-XSRF-TOKEN":void 0}}},watch:{formValid:function(e){this.$emit("validStatus",e)}},methods:{resetValidation:function(){this.$refs.form.resetValidation()},onUpload:function(){this.$store.dispatch("snackbar/setSnackbar",{text:"Зображення було збережено !"})}}},n=(r(580),r(26)),l=r(64),c=r.n(l),d=r(579),f=r(583),m=r(587),w=r(710),v=r(499),component=Object(n.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("section",{staticClass:"profileForm"},[r("div",{staticClass:"profileForm__l"},[r("VueFileAgent",{ref:"vueFileAgent",staticClass:"avatarUpload",attrs:{multiple:!1,maxFiles:"1",deletable:!1,uploadHeaders:e.getXSRF,maxSize:"10MB",maxFiles:1,accept:".jpg, .png",meta:!1,helpText:"Завантажити фото",errorText:{type:"Обраний тип файлу не підтримується",size:"Розмір файлу має бути менше 10MB"},uploadUrl:e.avatarUploadApi},on:{upload:function(t){return e.onUpload(t)}},model:{value:e.fileRecords,callback:function(t){e.fileRecords=t},expression:"fileRecords"}})],1),e._v(" "),r("div",{staticClass:"profileForm__r"},[r("v-form",{ref:"form",model:{value:e.formValid,callback:function(t){e.formValid=t},expression:"formValid"}},[r("v-container",[r("v-row",{staticClass:"profileForm__row"},e._l(e.data,(function(t,o){return r("v-col",{key:o,staticClass:"profileForm__col",attrs:{cols:"12",md:"6"}},[r("v-text-field","phone"===o?{directives:[{name:"facade",rawName:"v-facade",value:"(###) ###-##-##",expression:"'(###) ###-##-##'"}],staticClass:"profileForm__ctrl",attrs:{label:t.label,rules:e.phoneRules,required:"",outlined:"",rounded:""},model:{value:t.value,callback:function(r){e.$set(t,"value",r)},expression:"f.value"}}:"email"===o?{staticClass:"profileForm__ctrl",attrs:{label:t.label,rules:e.emailRules,required:"",outlined:"",rounded:""},model:{value:t.value,callback:function(r){e.$set(t,"value",r)},expression:"f.value"}}:"geo"===o?{directives:[{name:"facade",rawName:"v-facade",value:"##.######, ##.######",expression:"'##.######, ##.######'"}],staticClass:"profileForm__ctrl",attrs:{label:t.label,rules:e.geoRules,required:"",outlined:"",rounded:"","append-icon":"mdi-map-marker"},model:{value:t.value,callback:function(r){e.$set(t,"value",r)},expression:"f.value"}}:{staticClass:"profileForm__ctrl",attrs:{label:t.label,rules:e.nameRules,required:"",outlined:"",rounded:""},model:{value:t.value,callback:function(r){e.$set(t,"value",r)},expression:"f.value"}})],1)})),1)],1)],1)],1)])}),[],!1,null,"7924df9c",null);t.default=component.exports;c()(component,{VCol:d.a,VContainer:f.a,VForm:m.a,VRow:w.a,VTextField:v.a})},584:function(e,t,r){"use strict";r(577)},585:function(e,t,r){var o=r(15)(!1);o.push([e.i,".passwordForm__instance[data-v-15f1cdab]{width:100%}.passwordForm__row[data-v-15f1cdab]{justify-content:center}.passwordForm__row--actions[data-v-15f1cdab]{margin-top:10px;text-align:center}.passwordForm__btn[data-v-15f1cdab]{min-width:200px!important}.passwordForm__btn+.passwordForm__btn[data-v-15f1cdab]{margin-left:10px}",""]),e.exports=o},588:function(e,t,r){"use strict";r.r(t);r(25),r(5),r(204);var o={name:"ChangePasswordForm",props:{ChangePasswordFormSmb:{type:Function,required:!0},loading:{type:Boolean}},data:function(){var e=this;return{showPassword:!1,showPasswordNew:!1,formData:{old_password:"",new_password:"",repeat_password:""},formValid:!1,passwordRules:[function(t){return!!t||e.$store.state.gui.errors.formRequire},function(t){return/^(?!.*\s)/.test(t)||e.$store.state.gui.errors.formValid},function(e){return e.length<=40||"Поле повинно мати, менше ніж 40 символів"},function(e){return e.length>=8||"Поле повинно мати, хоча  8 символи"}],passwordRulesRepeat:[function(t){return!!t||e.$store.state.gui.errors.formRequire},function(t){return/^(?!.*\s)/.test(t)||e.$store.state.gui.errors.formValid},function(t){return e.compareNewPasswords||"Поле не співпадає з новим паролем!"}]}},computed:{msg:function(){return this.$store.state.gui.messages},isNotEmpty:function(){return Object.values(this.formData).some((function(e){return e.length>0}))},compareNewPasswords:function(){return this.formData.new_password===this.formData.repeat_password},showSubmit:function(){return this.formValid&&this.compareNewPasswords}},methods:{resetValidation:function(){this.$refs.form.resetValidation()},cancelForm:function(){this.formData.old_password="",this.formData.new_password="",this.formData.repeat_password="",this.resetValidation()},submitForm:function(data){if(!this.formValid)return this.$store.dispatch("snackbar/setSnackbar",{color:"red",text:this.$store.state.gui.errors.formValid,timeout:4e3}),!1;this.ChangePasswordFormSmb(data)}}},n=(r(584),r(26)),l=r(64),c=r.n(l),d=r(451),f=r(579),m=r(583),w=r(587),v=r(222),h=r(710),_=r(499),component=Object(n.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("section",{staticClass:"passwordForm"},[r("v-form",{ref:"form",staticClass:"passwordForm__instance",on:{submit:function(t){return t.preventDefault(),e.submitForm(e.formData)}},model:{value:e.formValid,callback:function(t){e.formValid=t},expression:"formValid"}},[r("v-container",[r("v-row",{staticClass:"passwordForm__row"},[r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"3"}},[r("v-text-field",{staticClass:"passwordForm__ctrl",attrs:{label:e.msg.password,rules:e.passwordRules,required:"",rounded:"",outlined:"",type:"password",type:e.showPassword?"text":"password","append-icon":e.showPassword?"mdi-eye":"mdi-eye-off"},on:{"click:append":function(t){e.showPassword=!e.showPassword}},model:{value:e.formData.old_password,callback:function(t){e.$set(e.formData,"old_password",t)},expression:"formData.old_password"}})],1),e._v(" "),r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"3"}},[r("v-text-field",{staticClass:"passwordForm__ctrl",attrs:{label:e.msg.passwordNew,rules:e.passwordRules,required:"",rounded:"",autocomplete:"new-password",type:"password",outlined:"",type:e.showPasswordNew?"text":"password","append-icon":e.showPasswordNew?"mdi-eye":"mdi-eye-off"},on:{"click:append":function(t){e.showPasswordNew=!e.showPasswordNew}},model:{value:e.formData.new_password,callback:function(t){e.$set(e.formData,"new_password",t)},expression:"formData.new_password"}})],1),e._v(" "),r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"3"}},[r("v-text-field",{staticClass:"passwordForm__ctrl",attrs:{label:e.msg.passwordNewRepeat,rules:e.passwordRulesRepeat,required:"",rounded:"",autocomplete:"none",type:"password",outlined:"",type:e.showPasswordNew?"text":"password","append-icon":e.showPasswordNew?"mdi-eye":"mdi-eye-off"},on:{"click:append":function(t){e.showPasswordNew=!e.showPasswordNew}},model:{value:e.formData.repeat_password,callback:function(t){e.$set(e.formData,"repeat_password",t)},expression:"formData.repeat_password"}})],1)],1),e._v(" "),r("v-row",{staticClass:"passwordForm__row passwordForm__row--actions"},[r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"12"}},[r("v-btn",{staticClass:"passwordForm__btn",attrs:{large:"",rounded:"",disabled:!e.isNotEmpty},on:{click:e.cancelForm}},[e._v("\n                        Скасувати\n                    ")]),e._v(" "),r("v-btn",{staticClass:"passwordForm__btn passwordForm__btn--smb",attrs:{large:"",rounded:"",loading:e.loading,disabled:!e.showSubmit||e.loading,color:"primary"},on:{click:function(t){return e.submitForm(e.formData)}},scopedSlots:e._u([{key:"loader",fn:function(){return[r("span",{staticClass:"custom-loader"},[r("v-icon",{attrs:{light:""}},[e._v("mdi-cached")])],1)]},proxy:!0}])},[e._v("\n                        Зберегти\n                        ")])],1)],1)],1)],1)],1)}),[],!1,null,"15f1cdab",null);t.default=component.exports;c()(component,{VBtn:d.a,VCol:f.a,VContainer:m.a,VForm:w.a,VIcon:v.a,VRow:h.a,VTextField:_.a})},595:function(e,t,r){"use strict";(function(e){var o=r(21),n=(r(63),r(14),r(25),r(65),r(67),r(582)),l=r(588);t.a={name:"ProfilePage",components:{ProfileForm:n.default,ChangePasswordForm:l.default},data:function(){return{pageTitle:"",pageSubTitle:"",pageSubTitle2:"",profileForm:{},userBackup:{},formValid:!1,loading:!1}},asyncData:function(t){var r=t.$axios,o=t.error,n="api"+t.route.fullPath;return r.get(n).then((function(t){if(!t.data.success)throw o;return{pageTitle:t.data.pageTitle,pageSubTitle:t.data.pageSubTitle,pageSubTitle2:t.data.pageSubTitle2,userBackup:e.cloneDeep(t.data.user),profileForm:t.data.user.form}})).catch((function(e){o({statusCode:404,message:"Error axios"})}))},methods:{logOut:function(){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.$auth.logout().then((function(){e.$router.push("/")}));case 2:case"end":return t.stop()}}),t)})))()},changeFormValidStatus:function(){var e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.formValid=e},ProfileFormReset:function(){this.profileForm=e.cloneDeep(this.userBackup.form)},ProfileFormSmb:function(){var e=this;if(this.formValid){var t={id:this.userBackup.id,data:[]},r={};return Object.keys(this.profileForm).map((function(t){null!==e.profileForm[t].value&&(r[t]="phone"===t?e.profileForm[t].value.replace(/\D/g,""):e.profileForm[t].value)})),t.data=r,this.$axios.$post("/api/"+this.userBackup.objType,t).then((function(t){e.$store.dispatch("snackbar/setSnackbar",{text:"Данні було збережено !"}),e.$nuxt.refresh()})).catch((function(t){console.warn(t),e.$store.dispatch("snackbar/setSnackbar",{color:"red",text:e.$store.state.gui.errors.general})}))}this.$store.dispatch("snackbar/setSnackbar",{color:"red",text:"Перевірте правильність заповнення форми !"})},ChangePasswordFormSmb:function(data){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.loading=!0,"/api/users/password",t.prev=2,t.next=5,e.$axios.post("/api/users/password",data).then((function(t){t.data.success&&e.$store.dispatch("snackbar/setSnackbar",{text:t.data.message}),e.loading=!1,e.logOut().then(e.$store.dispatch("snackbar/setSnackbar",{color:"secondary",text:e.msg.reSignIn}))}));case 5:return t.abrupt("return",t.sent);case 8:t.prev=8,t.t0=t.catch(2),403===t.t0.response.status?e.$store.dispatch("snackbar/setSnackbar",{color:"red",text:t.t0.response.data.message,timeout:4e3}):e.$store.dispatch("snackbar/setSnackbar",{color:"red",text:t.t0.message,timeout:4e3}),e.loading=!1;case 12:case"end":return t.stop()}}),t,null,[[2,8]])})))()}},computed:{msg:function(){return this.$store.state.gui.messages},isProfileFormChanged:function(){var t=e.cloneDeep(this.profileForm);this.userBackup.form;return t.phone.value&&(t.phone.value=t.phone.value.replace(/\D/g,"")),!e.isEqual(t,this.userBackup.form)},avatarUploadApi:function(){return"/api/"+this.userBackup.objType+"/avatar"}}}}).call(this,r(302))},613:function(e,t,r){var content=r(674);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(16).default)("03d652c4",content,!0,{sourceMap:!1})},673:function(e,t,r){"use strict";r(613)},674:function(e,t,r){var o=r(15)(!1);o.push([e.i,".formActions[data-v-46b83a4a]{display:flex;justify-content:center}.formActions__btn[data-v-46b83a4a]{min-width:190px!important}.formActions__btn+.formActions__btn[data-v-46b83a4a]{margin-left:20px}",""]),e.exports=o},732:function(e,t,r){"use strict";r.r(t);var o=r(595).a,n=(r(673),r(26)),l=r(64),c=r.n(l),d=r(451),component=Object(n.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[r("h1",{staticClass:"page-ttl"},[e._v(e._s(e.pageTitle||"Admin Profile page"))]),e._v(" "),r("div",{staticClass:"eecuData"},[r("h2",{staticClass:"eecuData__ttl"},[e._v(e._s(e.pageSubTitle||"Редагувати"))]),e._v(" "),r("ProfileForm",{attrs:{data:e.profileForm,avatarUploadApi:e.avatarUploadApi},on:{"update:data":function(t){e.profileForm=t},validStatus:e.changeFormValidStatus}}),e._v(" "),r("div",{staticClass:"formActions"},[r("v-btn",{staticClass:"formActions__btn",attrs:{large:"",rounded:"",disabled:!e.isProfileFormChanged},on:{click:e.ProfileFormReset}},[e._v("\n                Скасувати\n            ")]),e._v(" "),r("v-btn",{staticClass:"formActions__btn formActions__btn--smb",attrs:{large:"",rounded:"",disabled:!e.formValid||!e.isProfileFormChanged,color:"primary"},on:{click:e.ProfileFormSmb}},[e._v("\n                Зберегти\n            ")])],1)],1),e._v(" "),r("div",{staticClass:"eecuData"},[r("h2",{staticClass:"eecuData__ttl"},[e._v(e._s(e.pageSubTitle2||"Змінити пароль"))]),e._v(" "),r("ChangePasswordForm",{attrs:{ChangePasswordFormSmb:e.ChangePasswordFormSmb,loading:e.loading}})],1)])}),[],!1,null,"46b83a4a",null);t.default=component.exports;c()(component,{VBtn:d.a})}}]);