(window.webpackJsonp=window.webpackJsonp||[]).push([[44],{476:function(t,e,r){var content=r(486);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("831bfffc",content,!0,{sourceMap:!1})},477:function(t,e,r){var content=r(490);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("60033736",content,!0,{sourceMap:!1})},485:function(t,e,r){"use strict";r(476)},486:function(t,e,r){var o=r(15),n=r(487),l=r(488),c=o(!1),d=n(l);c.push([t.i,'.eecu-progress[data-v-7e596a21]{display:flex;align-items:center;max-width:125px;background:var(--bg)}.eecu-progress__graph[data-v-7e596a21]{background:#bdbdbd;opacity:1}.eecu-progress__graph[data-v-7e596a21]:before{content:"";position:absolute;top:0;right:0;bottom:0;left:0;z-index:1;background:url('+d+") 50% 50%/cover no-repeat}.eecu-progress__val[data-v-7e596a21]{margin-left:8px;color:var(--v-primary-base);font-weight:500;font-size:12px;line-height:180%}",""]),t.exports=c},488:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFYAAAAOCAYAAAC1i+ttAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAABFSURBVHgB7dAhEgAgEMPAHv//MxwWBzMxTFZWVKTyYLZzq5ZLv/5sI0IYFmJYiGEhhoUYFmJYiGEhhoUYFmJYiGEhhoUs4t4QHER9EHwAAAAASUVORK5CYII="},489:function(t,e,r){"use strict";r(477)},490:function(t,e,r){var o=r(15)(!1);o.push([t.i,".eecu-progress .v-progress-linear__background{opacity:.15!important}.eecu-progress .v-progress-linear__buffer{display:none}",""]),t.exports=o},492:function(t,e,r){"use strict";r.r(e);r(24);var o={name:"Progress",props:{data:{type:Number,required:!0},color:{type:String,default:"primary"},bg:{type:String,default:"#EEF2F4"}},computed:{cssProps:function(){return{"--bg":this.bg}}},methods:{}},n=(r(485),r(489),r(26)),l=r(64),c=r.n(l),d=r(190),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"eecu-progress"},[r("v-progress-linear",{staticClass:"eecu-progress__graph",attrs:{value:t.data,color:t.color,height:"13"}}),t._v(" "),r("span",{staticClass:"eecu-progress__val"},[t._v(t._s(t.data+"%"))])],1)}),[],!1,null,"7e596a21",null);e.default=component.exports;c()(component,{VProgressLinear:d.a})},539:function(t,e,r){"use strict";(function(t){r(25),r(203),r(14),r(33),r(213);e.a={name:"CustomFilter",props:{category:String,search:Object,type:Object,measure:Object,source:Object},computed:{showCancel:function(){return null===this.measureMod&&(this.measureMod={}),null===this.typeMod&&(this.typeMod=""),null===this.sourceMod&&(this.sourceMod=""),this.search?!(!this.query&&0===Object.keys(this.measureMod).length&&!this.sourceMod):!(!this.typeMod&&0===Object.keys(this.measureMod).length&&!this.sourceMod)},resetText:function(){return this.windowWidth<960||this.windowWidth>1600?"Скасувати":""}},data:function(){return{windowWidth:0,typeMod:"",measureMod:{},sourceMod:"",valid:!1,query:""}},methods:{getFilterId:function(filter){if(filter)return+filter},getSelect:function(t){"sector"===this.category&&this.$emit("set-sector",t.value)},getParams:function(){if(this.showCancel){var t={};this.search?(t.search=this.query,t.measure=this.measureMod):(t.type=this.typeMod,t.format=this.measureMod),t.source=this.sourceMod}},clearFilter:function(){this.destroyFilter(),this.$emit("clear-filter")},destroyFilter:function(){this.query="",this.typeMod="",this.measureMod={},this.sourceMod=""},sendParams:function(t,e){return this.$axios.get(e,t).then((function(t){return{blockTtl:t.data.name,dataTable:t.data}})).catch((function(t){error({statusCode:404,message:"Error axios"})}))},updateFilter:function(){Object.entries(this.$route.query).length&&(this.query=this.$route.query.search,this.measureMod.value=this.getFilterId(this.$route.query.measure),this.sourceMod=this.getFilterId(this.$route.query.source))},updateQuery:function(){if(this.showCancel&&""!==this.showCancel){var t={};this.measureMod&&(t.measure=this.measureMod.value),this.sourceMod&&(t.source=this.sourceMod),this.search?this.query&&(t.search=this.query):""!==this.typeMod&&(t.type=this.typeMod),this.$emit("show-results",t)}else this.$emit("clear-filter")},handleResize:function(){this.windowWidth=window.innerWidth}},created:function(){Object.entries(this.$route.query).length&&(this.query=this.$route.query.search,this.$route.query.type&&(this.typeMod=this.getFilterId(this.$route.query.type)),this.measureMod.value=this.getFilterId(this.$route.query.measure),this.sourceMod=this.getFilterId(this.$route.query.source))},mounted:function(){this.handleResize(),window.addEventListener("resize",t.debounce(this.handleResize))},beforeDestroy:function(){window.removeEventListener("resize",t.debounce(this.handleResize))}}}).call(this,r(302))},542:function(t,e,r){var content=r(561);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("75efb34c",content,!0,{sourceMap:!1})},544:function(t,e,r){"use strict";r.r(e);var o=r(539).a,n=(r(560),r(26)),l=r(64),c=r.n(l),d=r(451),h=r(579),f=r(587),v=r(592),_=r(540),m=r(710),y=r(517),x=r(499),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-form",{staticClass:"eecuFilter mb-5",model:{value:t.valid,callback:function(e){t.valid=e},expression:"valid"}},[r("v-row",{staticClass:"eecuFilter__row"},[r("v-col",{staticClass:"eecuFilter__col",attrs:{cols:"4",md:"3"}},[t.search?r("v-text-field",{staticClass:"eecuFilter__ctrl",attrs:{solo:"",rounded:"",label:t.search.name,required:"","hide-details":""},model:{value:t.query,callback:function(e){t.query=e},expression:"query"}}):r("v-select",{staticClass:"eecuFilter__select",attrs:{color:"primary",solo:"",clearable:"",rounded:"","hide-details":"",items:t.type.list,"item-text":"name","item-value":"value",label:t.type.name},scopedSlots:t._u([{key:"selection",fn:function(data){return[t._v("\n                            "+t._s(data.item.name)+"\n                        ")]}},{key:"item",fn:function(e){e.active;var o=e.item,n=e.attrs,l=e.on;return[r("v-list-item",t._g(t._b({scopedSlots:t._u([{key:"default",fn:function(e){e.active;return[r("Icon",{attrs:{icon:o.icon||"fe:list-task"}}),t._v(" "),r("v-list-item-content",[r("v-list-item-title",[r("v-row",{attrs:{"no-gutters":"",align:"center"}},[r("span",[t._v(t._s(o.name))])])],1)],1)]}}],null,!0)},"v-list-item",n,!1),l))]}}]),model:{value:t.typeMod,callback:function(e){t.typeMod=e},expression:"typeMod"}})],1),t._v(" "),r("v-col",{staticClass:"eecuFilter__col",attrs:{cols:"4",md:"3"}},[r("v-select",{staticClass:"eecuFilter__select",attrs:{color:"primary","return-object":"",solo:"",clearable:"",rounded:"","hide-details":"",items:t.measure.list,"item-text":"name","item-value":"value",label:t.measure.name},on:{change:function(e){return t.getSelect(t.measureMod)}},model:{value:t.measureMod,callback:function(e){t.measureMod=e},expression:"measureMod"}})],1),t._v(" "),r("v-col",{staticClass:"eecuFilter__col",attrs:{cols:"4",md:"3"}},[r("v-select",{staticClass:"eecuFilter__select",attrs:{solo:"",clearable:"",rounded:"","hide-details":"",items:t.source.list,"item-text":"name","item-value":"value",label:t.source.name,"no-data-text":"404"},model:{value:t.sourceMod,callback:function(e){t.sourceMod=e},expression:"sourceMod"}})],1),t._v(" "),r("v-col",{staticClass:"eecuFilter__col eecuFilter__actions ml-sm-auto",attrs:{cols:"12",md:"3"}},[r("v-btn",{staticClass:"eecuFilter__btn",attrs:{rounded:"",large:"",color:"primary"},on:{click:t.updateQuery}},[t._v("\n                        Показати\n                    ")]),t._v(" "),t.showCancel?r("v-btn",{staticClass:"eecuFilter__btn filterReset",attrs:{rounded:"",large:"",depressed:"",text:""},on:{click:t.clearFilter}},[r("Icon",{staticClass:"filterReset__ico",attrs:{icon:"codicon:refresh"}}),t._v("\n                        "+t._s(t.resetText)+"\n                    ")],1):t._e()],1)],1)],1)}),[],!1,null,"70744726",null);e.default=component.exports;c()(component,{VBtn:d.a,VCol:h.a,VForm:f.a,VListItem:v.a,VListItemContent:_.a,VListItemTitle:_.b,VRow:m.a,VSelect:y.a,VTextField:x.a})},560:function(t,e,r){"use strict";r(542)},561:function(t,e,r){var o=r(15)(!1);o.push([t.i,".eecuFilter__btn[data-v-70744726],.eecuFilter__ctrl[data-v-70744726],.eecuFilter__select[data-v-70744726]{mix-blend-mode:normal;border:1px solid #fff;box-shadow:0 30px 40px rgba(74,85,188,.137074);-webkit-backdrop-filter:blur(19.028px);backdrop-filter:blur(19.028px);border-radius:20px}.eecuFilter__actions[data-v-70744726]{display:flex}.eecuFilter__col>.v-btn.v-size--large[data-v-70744726]{height:48px}.filterReset[data-v-70744726]{margin-left:7px}.filterReset__ico[data-v-70744726]{font-size:18px;display:inline-block;vertical-align:top;margin-right:6px}",""]),t.exports=o},618:function(t,e,r){var content=r(688);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("56d70fc0",content,!0,{sourceMap:!1})},619:function(t,e,r){var content=r(690);content.__esModule&&(content=content.default),"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,r(16).default)("120c6a77",content,!0,{sourceMap:!1})},687:function(t,e,r){"use strict";r(618)},688:function(t,e,r){var o=r(15)(!1);o.push([t.i,".eecu-tooltip__ico[data-v-4376d0c1]{display:inline-block;vertical-align:top;transform:translate(-3px,1px);cursor:pointer;font-size:16px;line-height:1}.tooltip-infolist[data-v-4376d0c1]{list-style:none;display:flex;padding:14px 30px 12px 17px;flex-flow:column nowrap;font-size:13px;line-height:170%;color:#828282;border-radius:10px}.tooltip-infolist__el[data-v-4376d0c1]{display:inline-flex;align-items:center}.tooltip-infolist__el+.tooltip-infolist__el[data-v-4376d0c1]{margin-top:5px}.tooltip-infolist__ttl[data-v-4376d0c1]{font-weight:600;margin-bottom:10px}.tooltip-infolist__ico[data-v-4376d0c1]{display:inline;vertical-align:top;margin-right:10px;font-size:15px;max-width:20px;text-align:center}.tooltip-infolist__text[data-v-4376d0c1]{max-width:200px}",""]),t.exports=o},689:function(t,e,r){"use strict";r(619)},690:function(t,e,r){var o=r(15)(!1);o.push([t.i,".v-card.v-sheet.v-sheet--outlined.theme--light.rounded{margin:12px;padding:12px}.v-card__actions>.v-btn.v-btn--rounded.primary{padding:0 20px;border-radius:20px}",""]),t.exports=o},724:function(t,e,r){"use strict";r.r(e);var o=r(2);r(13),r(5),r(25),r(203),r(14),r(10),r(17),r(11),r(18);function n(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,r)}return e}var l={name:"ManagerRatingYearPage",components:{Progress:r(492).default},watchQuery:!0,data:function(){return{param:JSON.stringify(this.$route.params),selectedItem:[],pageTitle:"ManagerRatingYearPage",status:{},search:{name:"Пошук за назвою ...",value:""},filter:{measure:{},source:{}},group:[],files:{list:[]}}},computed:{getProgressColor:function(){return"dataentry"===this.status.key?"yellow":"green"}},asyncData:function(t){var e=t.$axios,r=t.error,o=t.params;return console.log("asyncData"),e.get("/api/manager/rating/"+o.year).then((function(t){if(!t.data.success)throw r;return{pageTitle:t.data.pageTitle,filter:t.data.filter,files:t.data.files,group:t.data.group,search:t.data.search,status:t.data.status,table:t.data.table,communityId:t.data.community_id,yearId:t.data.year_id}})).catch((function(t){r({statusCode:404,message:"Error axios"})}))},methods:{reload:function(){this.$nuxt.refresh()},pageActionsHandle:function(t,e){var r=this,l=function(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?n(Object(source),!0).forEach((function(e){Object(o.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):n(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}({},t),c=l.key,d=this.$store.state.gui.claimStatus[c];if("func"===e){if(!t.evtName)return void console.warn("evtName isnt provide !");var h="/api/"+t.objType+"/"+this.communityId+"/"+this.yearId,f={evtName:t.evtName};this.$store.dispatch("sendRequest",{api:h,method:"post",payload:f}).then((function(t){r.reload()}))}return d&&d[e]?d[e]:""},showFiles:function(){this.$router.push({path:this.$route.path+"/files"})},showGroup:function(t){this.$router.push({path:this.$route.path+"/metric",query:{measure:t}})},showResults:function(filter){this.$router.push({path:this.$route.path+"/metric",query:filter})},clearFilter:function(){this.$router.push({path:this.$route.path})}}},c=l,d=(r(687),r(689),r(26)),h=r(64),f=r.n(h),v=r(451),_=r(189),m=r(83),y=r(579),x=r(710),C=r(586),w=r(550),component=Object(d.a)(c,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("h1",{staticClass:"page-ttl"},[t._v("\n            "+t._s(t.pageTitle+" "+t.$route.params.year)+"\n\n            "),t.status?r("v-btn",{staticClass:"pageAction",style:{pointerEvents:t.status.isAction?"initial":"none"},attrs:{rounded:"",large:"",outlined:"",color:t.pageActionsHandle(t.status,"color"),title:t.status.name},on:{click:function(e){return t.pageActionsHandle(t.status,"func")}}},[r("i",{staticClass:"pageAction__ico"},[r("Icon",{attrs:{icon:t.status.icon,color:t.pageActionsHandle(t.status,"color")}})],1),t._v("\n                "+t._s(t.status.name)+"\n            ")]):t._e()],1),t._v(" "),r("v-spacer",{staticClass:"mb-10"}),t._v(" "),r("CustomFilter",{attrs:{category:"search",search:t.search,measure:t.filter.measure,source:t.filter.source},on:{"show-results":t.showResults,"clear-filter":t.clearFilter}}),t._v(" "),r("v-spacer",{staticClass:"mb-15"}),t._v(" "),r("h2",[t._v("Групи показників")]),t._v(" "),r("v-spacer",{staticClass:"mb-10"}),t._v(" "),r("v-row",{staticClass:"catRow"},t._l(t.group,(function(e,o){return r("v-col",{key:o,staticClass:"catCol",attrs:{md:"4"}},[r("v-card",{staticClass:"catCard rounded-xl",attrs:{outlined:"",rounded:""}},[r("v-card-text",[r("p",{staticClass:"text-h5 text--primary catCard__ttl"},[t._v("\n                                "+t._s(e.name)+"\n                            ")]),t._v(" "),r("Progress",{staticClass:"catCard__stat",attrs:{data:e.progress,color:t.getProgressColor}}),t._v(" "),r("img",{staticClass:"catCard__pic",attrs:{src:e.pic}})],1),t._v(" "),r("v-card-actions",[r("v-btn",{attrs:{color:"primary",rounded:"",large:""},on:{click:function(r){return t.showGroup(e.filterValue)}}},[t._v("\n                                "+t._s(e.btnText||"Перейти")+"\n                            ")])],1)],1)],1)})),1),t._v(" "),r("v-spacer",{staticClass:"mb-10"}),t._v(" "),r("div",{staticClass:"eecuData"},[r("div",{staticClass:"dwnl-head"},[r("h4",{staticClass:"dwnl-head__ttl"},[t._v("Останні завантажені файли")]),t._v(" "),r("div",{staticClass:"dwnl-head__r"},[r("v-btn",{staticClass:"eecuTable__uploadBtn",attrs:{rounded:"",large:"",color:""},on:{click:t.showFiles}},[t._v("\n                        Завантажити файли\n                    ")]),t._v(" "),r("div",{staticClass:"dwnl-count"},[t.files.tooltip?r("v-tooltip",{staticClass:"eecu-tooltip",attrs:{bottom:"",color:"#fff"},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[r("i",t._g({staticClass:"eecu-tooltip__ico"},o),[r("Icon",{attrs:{icon:"ant-design:info-circle-outlined"}})],1)]}}],null,!1,251661554)},[t._v(" "),r("ul",{staticClass:"tooltip-infolist"},[r("li",{staticClass:"tooltip-infolist__ttl"},[t._v("\n                                    "+t._s(t.files.tooltip.name)+"\n                                ")]),t._v(" "),t._l(t.files.tooltip.items,(function(e,i){return r("li",{key:"A"+i,staticClass:"tooltip-infolist__el"},[r("Icon",{staticClass:"tooltip-infolist__ico",attrs:{icon:e.icon}}),t._v(" "),r("span",{staticClass:"tooltip-infolist__text"},[t._v(t._s(e.name))])],1)}))],2)]):t._e(),t._v(" "),r("span",{staticClass:"dwnl-count__val"},[t._v("Кількість: "+t._s(t.files.list.length))])],1)],1)]),t._v(" "),r("div",{staticClass:"dwnl-body"},[r("ul",{staticClass:"eecu-files"},t._l(t.files.list,(function(e){return r("li",{staticClass:"eecu-files__el"},[r("a",{staticClass:"file-lnk",attrs:{href:e.path||"#",target:"_blank"}},[r("i",{staticClass:"file-lnk__ico"},[r("Icon",{attrs:{icon:e.icon}})],1),t._v(" "),r("span",{staticClass:"file-lnk__name"},[t._v(t._s(e.name))])])])})),0)]),t._v(" "),r("div",{staticClass:"dwnl-footer"},[r("v-btn",{attrs:{rounded:"",large:"",color:"primary"},on:{click:t.showFiles}},[t._v("\n                    Переглянути всі\n                ")])],1)])],1)}),[],!1,null,"4376d0c1",null);e.default=component.exports;f()(component,{CustomFilter:r(544).default}),f()(component,{VBtn:v.a,VCard:_.a,VCardActions:m.a,VCardText:m.b,VCol:y.a,VRow:x.a,VSpacer:C.a,VTooltip:w.a})}}]);