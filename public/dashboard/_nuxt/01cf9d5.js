(window.webpackJsonp=window.webpackJsonp||[]).push([[41],{562:function(t,e,r){var content=r(581);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("031eaef7",content,!0,{sourceMap:!1})},577:function(t,e,r){var content=r(585);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("50887592",content,!0,{sourceMap:!1})},580:function(t,e,r){"use strict";r(562)},581:function(t,e,r){var o=r(15)(!1);o.push([t.i,".profileForm[data-v-7924df9c]{position:relative}.debug[data-v-7924df9c]{position:absolute;right:0;top:-50px}",""]),t.exports=o},582:function(t,e,r){"use strict";r.r(e);r(25),r(65),r(168),r(303),r(84);var o={name:"ProfileForm",props:{data:{type:Object,required:!0},avatarUploadApi:{type:String,required:!0}},data:function(){var t=this;return{fileRecords:[],fileRecordsForUpload:[],formValid:!1,nameRules:[function(e){return!!e||t.$store.state.gui.errors.formRequire},function(t){return"string"==typeof t&&t.length<=25||"Поле повинно мати, менше ніж 25 символів"},function(t){return"string"==typeof t&&t.length>=3||"Поле повинно мати, більше ніж 3 символи"},function(e){return!/\d/.test(e)||t.$store.state.gui.errors.formValid}],emailRules:[function(e){return!!e||t.$store.state.gui.errors.formRequire},function(e){return/^\w+.?\w*@[a-zA-Z_]+?\.(?!ru$)[a-zA-Z]{2,}(\.(?!ru$)[a-zA-Z]{2,})?$/.test(e)||t.$store.state.gui.errors.formEmail}],phoneRules:[function(e){return!!e||t.$store.state.gui.errors.formRequire},function(e){return null!==e&&10===e.replace(/\D/g,"").length||t.$store.state.gui.errors.formPhone}],geoRules:[function(e){return!!e||t.$store.state.gui.errors.formRequire},function(e){return"string"==typeof e&&e.length>=20||t.$store.state.gui.errors.formValid}]}},computed:{getXSRF:function(){var t=document.cookie.match(new RegExp("(?:^|; )"+"XSRF-TOKEN".replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,"\\$1")+"=([^;]*)"));return t?{"X-XSRF-TOKEN":decodeURIComponent(t[1])}:{"X-XSRF-TOKEN":void 0}}},watch:{formValid:function(t){this.$emit("validStatus",t)}},methods:{resetValidation:function(){this.$refs.form.resetValidation()},onUpload:function(){this.$store.dispatch("snackbar/setSnackbar",{text:"Зображення було збережено !"})}}},n=(r(580),r(26)),l=r(64),c=r.n(l),d=r(579),f=r(583),m=r(587),v=r(710),w=r(499),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("section",{staticClass:"profileForm"},[r("div",{staticClass:"profileForm__l"},[r("VueFileAgent",{ref:"vueFileAgent",staticClass:"avatarUpload",attrs:{multiple:!1,maxFiles:"1",deletable:!1,uploadHeaders:t.getXSRF,maxSize:"10MB",maxFiles:1,accept:".jpg, .png",meta:!1,helpText:"Завантажити фото",errorText:{type:"Обраний тип файлу не підтримується",size:"Розмір файлу має бути менше 10MB"},uploadUrl:t.avatarUploadApi},on:{upload:function(e){return t.onUpload(e)}},model:{value:t.fileRecords,callback:function(e){t.fileRecords=e},expression:"fileRecords"}})],1),t._v(" "),r("div",{staticClass:"profileForm__r"},[r("v-form",{ref:"form",model:{value:t.formValid,callback:function(e){t.formValid=e},expression:"formValid"}},[r("v-container",[r("v-row",{staticClass:"profileForm__row"},t._l(t.data,(function(e,o){return r("v-col",{key:o,staticClass:"profileForm__col",attrs:{cols:"12",md:"6"}},[r("v-text-field","phone"===o?{directives:[{name:"facade",rawName:"v-facade",value:"(###) ###-##-##",expression:"'(###) ###-##-##'"}],staticClass:"profileForm__ctrl",attrs:{label:e.label,rules:t.phoneRules,required:"",outlined:"",rounded:""},model:{value:e.value,callback:function(r){t.$set(e,"value",r)},expression:"f.value"}}:"email"===o?{staticClass:"profileForm__ctrl",attrs:{label:e.label,rules:t.emailRules,required:"",outlined:"",rounded:""},model:{value:e.value,callback:function(r){t.$set(e,"value",r)},expression:"f.value"}}:"geo"===o?{directives:[{name:"facade",rawName:"v-facade",value:"##.######, ##.######",expression:"'##.######, ##.######'"}],staticClass:"profileForm__ctrl",attrs:{label:e.label,rules:t.geoRules,required:"",outlined:"",rounded:"","append-icon":"mdi-map-marker"},model:{value:e.value,callback:function(r){t.$set(e,"value",r)},expression:"f.value"}}:{staticClass:"profileForm__ctrl",attrs:{label:e.label,rules:t.nameRules,required:"",outlined:"",rounded:""},model:{value:e.value,callback:function(r){t.$set(e,"value",r)},expression:"f.value"}})],1)})),1)],1)],1)],1)])}),[],!1,null,"7924df9c",null);e.default=component.exports;c()(component,{VCol:d.a,VContainer:f.a,VForm:m.a,VRow:v.a,VTextField:w.a})},584:function(t,e,r){"use strict";r(577)},585:function(t,e,r){var o=r(15)(!1);o.push([t.i,".passwordForm__instance[data-v-15f1cdab]{width:100%}.passwordForm__row[data-v-15f1cdab]{justify-content:center}.passwordForm__row--actions[data-v-15f1cdab]{margin-top:10px;text-align:center}.passwordForm__btn[data-v-15f1cdab]{min-width:200px!important}.passwordForm__btn+.passwordForm__btn[data-v-15f1cdab]{margin-left:10px}",""]),t.exports=o},586:function(t,e,r){"use strict";r(541);var o=r(1);e.a=Object(o.i)("spacer","div","v-spacer")},588:function(t,e,r){"use strict";r.r(e);r(25),r(5),r(204);var o={name:"ChangePasswordForm",props:{ChangePasswordFormSmb:{type:Function,required:!0},loading:{type:Boolean}},data:function(){var t=this;return{showPassword:!1,showPasswordNew:!1,formData:{old_password:"",new_password:"",repeat_password:""},formValid:!1,passwordRules:[function(e){return!!e||t.$store.state.gui.errors.formRequire},function(e){return/^(?!.*\s)/.test(e)||t.$store.state.gui.errors.formValid},function(t){return t.length<=40||"Поле повинно мати, менше ніж 40 символів"},function(t){return t.length>=8||"Поле повинно мати, хоча  8 символи"}],passwordRulesRepeat:[function(e){return!!e||t.$store.state.gui.errors.formRequire},function(e){return/^(?!.*\s)/.test(e)||t.$store.state.gui.errors.formValid},function(e){return t.compareNewPasswords||"Поле не співпадає з новим паролем!"}]}},computed:{msg:function(){return this.$store.state.gui.messages},isNotEmpty:function(){return Object.values(this.formData).some((function(t){return t.length>0}))},compareNewPasswords:function(){return this.formData.new_password===this.formData.repeat_password},showSubmit:function(){return this.formValid&&this.compareNewPasswords}},methods:{resetValidation:function(){this.$refs.form.resetValidation()},cancelForm:function(){this.formData.old_password="",this.formData.new_password="",this.formData.repeat_password="",this.resetValidation()},submitForm:function(data){if(!this.formValid)return this.$store.dispatch("snackbar/setSnackbar",{color:"red",text:this.$store.state.gui.errors.formValid,timeout:4e3}),!1;this.ChangePasswordFormSmb(data)}}},n=(r(584),r(26)),l=r(64),c=r.n(l),d=r(451),f=r(579),m=r(583),v=r(587),w=r(222),h=r(710),_=r(499),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("section",{staticClass:"passwordForm"},[r("v-form",{ref:"form",staticClass:"passwordForm__instance",on:{submit:function(e){return e.preventDefault(),t.submitForm(t.formData)}},model:{value:t.formValid,callback:function(e){t.formValid=e},expression:"formValid"}},[r("v-container",[r("v-row",{staticClass:"passwordForm__row"},[r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"3"}},[r("v-text-field",{staticClass:"passwordForm__ctrl",attrs:{label:t.msg.password,rules:t.passwordRules,required:"",rounded:"",outlined:"",type:"password",type:t.showPassword?"text":"password","append-icon":t.showPassword?"mdi-eye":"mdi-eye-off"},on:{"click:append":function(e){t.showPassword=!t.showPassword}},model:{value:t.formData.old_password,callback:function(e){t.$set(t.formData,"old_password",e)},expression:"formData.old_password"}})],1),t._v(" "),r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"3"}},[r("v-text-field",{staticClass:"passwordForm__ctrl",attrs:{label:t.msg.passwordNew,rules:t.passwordRules,required:"",rounded:"",autocomplete:"new-password",type:"password",outlined:"",type:t.showPasswordNew?"text":"password","append-icon":t.showPasswordNew?"mdi-eye":"mdi-eye-off"},on:{"click:append":function(e){t.showPasswordNew=!t.showPasswordNew}},model:{value:t.formData.new_password,callback:function(e){t.$set(t.formData,"new_password",e)},expression:"formData.new_password"}})],1),t._v(" "),r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"3"}},[r("v-text-field",{staticClass:"passwordForm__ctrl",attrs:{label:t.msg.passwordNewRepeat,rules:t.passwordRulesRepeat,required:"",rounded:"",autocomplete:"none",type:"password",outlined:"",type:t.showPasswordNew?"text":"password","append-icon":t.showPasswordNew?"mdi-eye":"mdi-eye-off"},on:{"click:append":function(e){t.showPasswordNew=!t.showPasswordNew}},model:{value:t.formData.repeat_password,callback:function(e){t.$set(t.formData,"repeat_password",e)},expression:"formData.repeat_password"}})],1)],1),t._v(" "),r("v-row",{staticClass:"passwordForm__row passwordForm__row--actions"},[r("v-col",{staticClass:"passwordForm__col",attrs:{cols:"12",md:"12"}},[r("v-btn",{staticClass:"passwordForm__btn",attrs:{large:"",rounded:"",disabled:!t.isNotEmpty},on:{click:t.cancelForm}},[t._v("\n                        Скасувати\n                    ")]),t._v(" "),r("v-btn",{staticClass:"passwordForm__btn passwordForm__btn--smb",attrs:{large:"",rounded:"",loading:t.loading,disabled:!t.showSubmit||t.loading,color:"primary"},on:{click:function(e){return t.submitForm(t.formData)}},scopedSlots:t._u([{key:"loader",fn:function(){return[r("span",{staticClass:"custom-loader"},[r("v-icon",{attrs:{light:""}},[t._v("mdi-cached")])],1)]},proxy:!0}])},[t._v("\n                        Зберегти\n                        ")])],1)],1)],1)],1)],1)}),[],!1,null,"15f1cdab",null);e.default=component.exports;c()(component,{VBtn:d.a,VCol:f.a,VContainer:m.a,VForm:v.a,VIcon:w.a,VRow:h.a,VTextField:_.a})},596:function(t,e,r){"use strict";(function(t){var o=r(21),n=(r(67),r(25),r(65),r(63),r(14),r(582)),l=r(629),c=r(588);e.a={name:"CommunityPage",components:{ProfileForm:n.default,VerifyForm:l.default,ChangePasswordForm:c.default},data:function(){return{pageTitle:"Community page",pageSubTitle:"",pageSubTitle2:"",community:"",profileForm:{},profileFormApi:"",verify:{},verifyForm:{},verifyTtl:"",verifySubTtl:"",verifyStatus:"",formValid:!1}},asyncData:function(e){var r=e.$axios,o=e.error,n="api"+e.route.fullPath;return r.get(n).then((function(e){if(!e.data.success)throw o;return{pageTitle:e.data.pageTitle,pageSubTitle:e.data.pageSubTitle,pageSubTitle2:e.data.pageSubTitle2,community:t.cloneDeep(e.data.community),profileForm:e.data.community.profileForm,verify:t.cloneDeep(e.data.participant),verifyForm:e.data.participant.verifyForm,verifyTtl:e.data.participant.ttl,verifySubTtl:e.data.participant.subttl,verifyStatus:e.data.participant.status}})).catch((function(t){o({statusCode:404,message:"Error axios"})}))},computed:{isVerifyFormChanged:function(){return!t.isEqual(this.verifyForm,this.verify.verifyForm)},isProfileFormChanged:function(){var e=t.cloneDeep(this.profileForm);return e.phone.value&&(e.phone.value=e.phone.value.replace(/\D/g,"")),!t.isEqual(e,this.community.profileForm)},avatarUploadApi:function(){return"/api/"+this.community.objType+"/"+this.community.slug+"/avatar"}},methods:{verifyFormReset:function(){this.verifyForm=t.cloneDeep(this.verify.verifyForm)},verifyFormSubmit:function(){var t=this;return Object(o.a)(regeneratorRuntime.mark((function e(){var r,data;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="/api/"+t.verify.objType+"/"+t.community.id,data=t.verifyForm,e.prev=2,e.next=5,t.$axios.post(r,{data:data}).then((function(e){e.data.success&&(t.$store.dispatch("snackbar/setSnackbar",{text:e.data.message}),t.$nuxt.refresh()),t.loading=!1}));case 5:return e.abrupt("return",e.sent);case 8:e.prev=8,e.t0=e.catch(2),t.$store.dispatch("snackbar/setSnackbar",{color:"red",text:e.t0.message,timeout:4e3});case 11:case"end":return e.stop()}}),e,null,[[2,8]])})))()},changeFormValidStatus:function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.formValid=t},ProfileFormCancel:function(){this.profileForm=t.cloneDeep(this.community.profileForm)},ProfileFormSmb:function(){var t=this;if(this.formValid){var e={id:this.community.id,data:[]},r={};return Object.keys(this.profileForm).map((function(e){null!==t.profileForm[e].value&&(r[e]="phone"===e?t.profileForm[e].value.replace(/\D/g,""):t.profileForm[e].value)})),e.data=r,this.$axios.$post("/api/"+this.community.objType+"/"+this.community.id,e).then((function(e){t.$store.dispatch("snackbar/setSnackbar",{text:"Данні було збережено !"}),t.$nuxt.refresh()})).catch((function(e){console.warn(e),t.$store.dispatch("snackbar/setSnackbar",{color:"red",text:t.$store.state.gui.errors.general})}))}this.$store.dispatch("snackbar/setSnackbar",{color:"red",text:"Перевірте правильність заповнення форми !"})}}}}).call(this,r(302))},616:function(t,e,r){var content=r(684);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("22f964be",content,!0,{sourceMap:!1})},683:function(t,e,r){"use strict";r(616)},684:function(t,e,r){var o=r(15)(!1);o.push([t.i,".formActions[data-v-170c6c4e]{display:flex;justify-content:center}.formActions__btn[data-v-170c6c4e]{min-width:190px!important}.formActions__btn+.formActions__btn[data-v-170c6c4e]{margin-left:24px}.approveForm-profile[data-v-170c6c4e]{max-width:605px;margin-left:auto}",""]),t.exports=o},733:function(t,e,r){"use strict";r.r(e);var o=r(596).a,n=(r(683),r(26)),l=r(64),c=r.n(l),d=r(451),f=r(586),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("h1",{staticClass:"page-ttl"},[t._v(t._s(t.pageTitle))]),t._v(" "),r("v-spacer",{staticClass:"mb-5"}),t._v(" "),r("div",{staticClass:"eecuData"},[r("h2",{staticClass:"eecuData__ttl"},[t._v(t._s(t.pageSubTitle||"Редагувати"))]),t._v(" "),r("ProfileForm",{attrs:{data:t.profileForm,avatarUploadApi:t.avatarUploadApi},on:{"update:data":function(e){t.profileForm=e},validStatus:t.changeFormValidStatus}}),t._v(" "),r("div",{staticClass:"formActions"},[r("v-btn",{staticClass:"formActions__btn",attrs:{large:"",rounded:"",disabled:!t.isProfileFormChanged},on:{click:t.ProfileFormCancel}},[t._v("\n                    Скасувати\n                ")]),t._v(" "),r("v-btn",{staticClass:"formActions__btn formActions__btn--smb",attrs:{large:"",rounded:"",disabled:!t.formValid||!t.isProfileFormChanged,color:"primary"},on:{click:t.ProfileFormSmb}},[t._v("\n                    Зберегти\n                ")])],1)],1),t._v(" "),r("v-spacer",{staticClass:"mb-15"}),t._v(" "),r("VerifyForm",{attrs:{ttl:t.verifyTtl,subTtl:t.verifySubTtl,status:t.verifyStatus,formData:t.verifyForm,formReset:t.verifyFormReset,formSmb:t.verifyFormSubmit,changed:t.isVerifyFormChanged},on:{"update:formData":function(e){t.verifyForm=e},"update:form-data":function(e){t.verifyForm=e}}})],1)}),[],!1,null,"170c6c4e",null);e.default=component.exports;c()(component,{VBtn:d.a,VSpacer:f.a})}}]);