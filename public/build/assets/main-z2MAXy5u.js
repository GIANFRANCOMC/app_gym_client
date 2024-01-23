import{_ as E,o as d,e as i,y as p,f as e,q as u,l as m,m as f,z as C,F as v,i as A,a as D,b as K,c as w,g as O,s as B,t as b,h as q,v as R,r as _,j as h,w as V,k as P,n as k,p as F,u as U}from"./inputSelect-iyL7CFYm.js";import{a as I}from"./axios-OMshwncm.js";import{i as G}from"./inputSelect2-lGZPlumr.js";const L={name:"inputNumber",emits:["enterKeyPressed","update:modelValue"],props:{modelValue:{type:[String,Number],default:""},showDiv:{type:Boolean,required:!1,default:!1},divClass:{type:Array,required:!1,default:[]},title:{type:String,required:!1,default:""},titleClass:{type:Array,required:!1,default:["form-label"]},required:{type:Boolean,required:!1,default:!1},requiredLabel:{type:String,required:!1,default:"*"},requiredClass:{type:Array,required:!1,default:["text-danger","ms-1"]},addClass:{type:Array,required:!1,default:["form-control"]},placeholder:{type:String,required:!1,default:""},disabled:{type:Boolean,required:!1,default:!1},showTextBottom:{type:Boolean,required:!1,default:!1},textBottomType:{type:String,required:!1,default:"first"},textBottomClass:{type:Array,required:!1,default:["text-danger"]},textBottomInfo:{type:Array,required:!1,default:[]},xl:{type:[String,Number],required:!1,default:"12"},lg:{type:[String,Number],required:!1,default:"12"},md:{type:[String,Number],required:!1,default:"12"},sm:{type:[String,Number],required:!1,default:"12"}},computed:{textBottom(){return this.textBottomInfo.length>0?this.textBottomInfo[0]:""},divSizeClass(){return`form-group col-xl-${this.xl} col-lg-${this.lg} col-md-${this.md} col-sm-${this.sm}`}},methods:{emitValue(o){this.$emit("update:modelValue",o)},handleEnterKey(){this.$emit("enterKeyPressed")}}},z=["textContent"],j=["textContent"],M={class:"input-group"},H=["value","placeholder","disabled"],J={key:1},Q=["textContent"],W={class:"input-group"},X=["value","placeholder","disabled"],Y={key:0},Z=["textContent"];function $(o,t,s,n,r,l){return s.showDiv?(d(),i("div",{key:0,class:u([l.divSizeClass,...s.divClass])},[p(o.$slots,"default"),e("label",{class:u([...s.titleClass]),textContent:m(s.title)},null,10,z),s.required?(d(),i("label",{key:0,class:u([...s.requiredClass]),textContent:m(s.requiredLabel)},null,10,j)):f("",!0),e("div",M,[p(o.$slots,"inputGroupPrepend"),e("input",{type:"number",value:s.modelValue,onInput:t[0]||(t[0]=c=>l.emitValue(c.target.value)),class:u([...s.addClass]),placeholder:s.placeholder,disabled:s.disabled,onKeyup:t[1]||(t[1]=C((...c)=>l.handleEnterKey&&l.handleEnterKey(...c),["enter"]))},null,42,H),p(o.$slots,"inputGroupAppend")]),s.showTextBottom?(d(),i("div",J,[s.textBottomType==="first"?(d(),i("small",{key:0,class:u([...s.textBottomClass]),textContent:m(l.textBottom)},null,10,Q)):f("",!0)])):f("",!0)],2)):(d(),i(v,{key:1},[p(o.$slots,"default"),e("div",W,[p(o.$slots,"inputGroupPrepend"),e("input",{type:"number",value:s.modelValue,onInput:t[2]||(t[2]=c=>l.emitValue(c.target.value)),class:u([...s.addClass]),placeholder:s.placeholder,disabled:s.disabled,onKeyup:t[3]||(t[3]=C((...c)=>l.handleEnterKey&&l.handleEnterKey(...c),["enter"]))},null,42,X),p(o.$slots,"inputGroupAppend")]),s.showTextBottom?(d(),i("div",Y,[s.textBottomType==="first"?(d(),i("small",{key:0,class:u([...s.textBottomClass]),textContent:m(l.textBottom)},null,10,Z)):f("",!0)])):f("",!0)],64))}const ee=E(L,[["render",$]]),te={components:{inputDate:A,inputText:D,inputSelect:K,inputSelect2:G,inputNumber:ee},mounted:async function(){document.getElementById("menu-item-product-services").classList.add("active"),await this.initParams({}),await this.initOthers({}),await this.listProductServices({}),w()},data(){return{lists:{productServices:{filters:{general:""},records:{total:0}}},forms:{productServices:{add:{modals:{default:"addProductServiceModal"},select2:{},data:{name:"",description:"",price:"",status:""},options:{status:[{code:"active",label:"Activo"},{code:"inactive",label:"Inactivo"}]},errors:{}}}},generalConfiguration:O}},methods:{initParams({}){return new Promise(o=>{o(!0)})},initOthers({}){return new Promise(o=>{o(!0)})},listProductServices({url:o=null}){return new Promise(t=>{B({type:"list"});let s=o||`${k}/productServices/list`,n={},r={};r.general=this.lists.productServices.filters.general,n.params=r,I.get(s,n).then(l=>{this.lists.productServices.records=l.data}).catch(l=>{b({subtitle:l,type:"error"})}).finally(()=>{q(),w(),t(!0)})})},createProductService(){let o="createProductService";B({type:"saveForm"}),this.clearFormErrors({functionName:o});let t=`${k}/productServices`,s={};s.name=this.forms.productServices.add.data.name,s.description=this.forms.productServices.add.data.description,s.price=this.forms.productServices.add.data.price,s.status=this.forms.productServices.add.data.status,I.post(t,s).then(n=>{switch(n.status){case 200:this.clearForm({functionName:o}),b({subtitle:n.data.message,type:"success"});break}}).catch(n=>{switch(n.response.status){case 422:this.setFormErrors({functionName:o,errors:n.response.data.errors}),b({code:n.response.status,type:"error"});break}}).finally(()=>{q()})},clearForm({functionName:o}){switch(o){case"createProductService":this.forms.productServices.add.data.name="",this.forms.productServices.add.data.description="",this.forms.productServices.add.data.price="",this.forms.productServices.add.data.status="";break}},clearFormErrors({functionName:o}){switch(o){case"createProductService":let t=Object.keys(this.forms.productServices.add.errors);for(const s of t)this.forms.productServices.add.errors[s]=[];break}},setFormErrors({functionName:o,errors:t=[]}){switch(o){case"createProductService":this.forms.productServices.add.errors.name=t.name??[],this.forms.productServices.add.errors.description=t.description??[],this.forms.productServices.add.errors.price=t.price??[],this.forms.productServices.add.errors.status=t.status??[];break}},validateVariable({value:o}){return R({value:o})}}},se=e("h4",{class:"py-2 mb-4"},[e("span",{class:"text-muted fw-light"},[e("i",{class:"fa fa-home fa-2xs"}),F(" / ")]),e("span",{class:"text-uppercase"}," PRODUCTOS - SERVICIOS ")],-1),re={class:"d-flex flex-row mb-4"},oe={class:"align-self-start"},ae=e("i",{class:"fa fa-info-circle","data-bs-toggle":"tooltip","data-bs-placement":"top",title:"Búsqueda por: Nombre, Descripción.",role:"button"},null,-1),le=e("i",{class:"fa fa-search"},null,-1),de=[le],ie={class:"align-self-end ms-3"},ne=["data-bs-target"],ce=e("i",{class:"fa fa-plus"},null,-1),ue=e("span",{class:"ms-1"},"Agregar",-1),me=[ce,ue],pe={class:"card"},fe={class:"table-responsive text-nowrap"},he={class:"table table-hover"},ve=e("thead",{class:"table-light"},[e("tr",{class:"text-uppercase"},[e("th",{class:"align-middle text-center fw-bold col-1"},"NOMBRE"),e("th",{class:"align-middle text-center fw-bold col-1"},"DESCRIPCIÓN"),e("th",{class:"align-middle text-center fw-bold col-1"},"PRECIO"),e("th",{class:"align-middle text-center fw-bold col-1"},"ESTADO")])],-1),be={class:"table-border-bottom-0"},_e=["textContent"],xe=["textContent"],ge=["textContent"],ye={class:"text-center"},Se=["textContent"],Ce={key:1},we=["textContent"],Be={class:"d-flex justify-content-center"},qe={key:0,"aria-label":"Page navigation",class:"mt-3"},Ve={class:"pagination d-flex flex-wrap"},Pe=["onClick","innerHTML"],ke=["id"],Ie={class:"modal-dialog modal-dialog-centered",role:"document"},Ee={class:"modal-content"},Te=e("div",{class:"modal-header"},[e("h5",{class:"modal-title text-uppercase"},"AGREGAR PRODUCTO - SERVICIO"),e("button",{type:"button",class:"btn-close","data-bs-dismiss":"modal","aria-label":"Close"})],-1),Ne={class:"modal-body"},Ae={class:"row g-2 mb-3"},De={class:"row g-2 mb-3"},Ke={class:"modal-footer"},Oe=e("button",{type:"button",class:"btn btn-label-secondary","data-bs-dismiss":"modal"},"Cerrar",-1),Re=e("i",{class:"fa fa-save"},null,-1),Fe=e("span",{class:"ms-1"},"Guardar",-1),Ue=[Re,Fe];function Ge(o,t,s,n,r,l){var x,g,y,S;const c=_("inputText"),T=_("inputNumber"),N=_("inputSelect");return d(),i(v,null,[se,e("div",re,[e("div",oe,[h(c,{modelValue:r.lists.productServices.filters.general,"onUpdate:modelValue":t[1]||(t[1]=a=>r.lists.productServices.filters.general=a),showDiv:!0,title:"Buscar",titleClass:["fw-bold","colon-at-end","ms-1"],placeholder:"Ingrese la búsqueda",onEnterKeyPressed:t[2]||(t[2]=a=>l.listProductServices({}))},{default:V(()=>[ae]),inputGroupAppend:V(()=>[e("button",{class:"btn btn-primary waves-effect",type:"button",onClick:t[0]||(t[0]=a=>l.listProductServices({}))},de)]),_:1},8,["modelValue"])]),e("div",ie,[e("button",{class:"btn btn-primary","data-bs-toggle":"modal","data-bs-target":`#${r.forms.productServices.add.modals.default}`},me,8,ne)])]),e("div",pe,[e("div",fe,[e("table",he,[ve,e("tbody",be,[r.lists.productServices.records.data&&r.lists.productServices.records.data.length>0?(d(!0),i(v,{key:0},P(r.lists.productServices.records.data,a=>(d(),i("tr",{key:a.id,class:"align-middle"},[e("td",{class:"text-center",textContent:m(a.name)},null,8,_e),e("td",{class:"text-center",textContent:m(a.description)},null,8,xe),e("td",{class:"text-center",textContent:m(a.price)},null,8,ge),e("td",ye,[e("span",{class:u(["badge","text-capitalize",{"bg-label-success":["active"].includes(a.status),"bg-label-danger":["inactive"].includes(a.status)}]),textContent:m(a.formatted_status)},null,10,Se)])]))),128)):(d(),i("tr",Ce,[e("td",{class:"text-center",colspan:"99",textContent:m(r.generalConfiguration.messages.withoutResults)},null,8,we)]))])])])]),e("div",Be,[r.lists.productServices.records.data&&r.lists.productServices.records.data.length>0?(d(),i("nav",qe,[e("ul",Ve,[(d(!0),i(v,null,P(r.lists.productServices.records.links,a=>(d(),i("li",{class:u(["page-item my-1",a.active?"active":a.url?"":"disabled"])},[e("a",{class:"page-link waves-effect me-1",href:"javascript:void(0);",onClick:ze=>l.listProductServices({url:a.url}),innerHTML:a.label},null,8,Pe)],2))),256))])])):f("",!0)]),e("div",{class:"modal fade",id:r.forms.productServices.add.modals.default,tabindex:"-1","aria-hidden":"true"},[e("div",Ie,[e("div",Ee,[Te,e("div",Ne,[e("div",Ae,[h(c,{modelValue:r.forms.productServices.add.data.name,"onUpdate:modelValue":t[3]||(t[3]=a=>r.forms.productServices.add.data.name=a),showDiv:!0,title:"Nombre",required:!0,maxlength:60,showTextBottom:!0,textBottomInfo:(x=r.forms.productServices.add.errors)==null?void 0:x.name,xl:6,lg:6,md:12,sm:12},null,8,["modelValue","textBottomInfo"]),h(c,{modelValue:r.forms.productServices.add.data.description,"onUpdate:modelValue":t[4]||(t[4]=a=>r.forms.productServices.add.data.description=a),showDiv:!0,title:"Descripción",required:!0,maxlength:80,showTextBottom:!0,textBottomInfo:(g=r.forms.productServices.add.errors)==null?void 0:g.description,xl:6,lg:6,md:12,sm:12},null,8,["modelValue","textBottomInfo"])]),e("div",De,[h(T,{modelValue:r.forms.productServices.add.data.price,"onUpdate:modelValue":t[5]||(t[5]=a=>r.forms.productServices.add.data.price=a),showDiv:!0,title:"Precio",required:!0,showTextBottom:!0,textBottomInfo:(y=r.forms.productServices.add.errors)==null?void 0:y.price,xl:6,lg:6,md:12,sm:12},null,8,["modelValue","textBottomInfo"]),h(N,{modelValue:r.forms.productServices.add.data.status,"onUpdate:modelValue":t[6]||(t[6]=a=>r.forms.productServices.add.data.status=a),options:r.forms.productServices.add.options.status,showDiv:!0,title:"Estado",required:!0,showTextBottom:!0,textBottomInfo:(S=r.forms.productServices.add.errors)==null?void 0:S.status,xl:6,lg:6,md:12,sm:12},null,8,["modelValue","options","textBottomInfo"])])]),e("div",Ke,[Oe,e("button",{type:"button",class:"btn btn-primary",onClick:t[7]||(t[7]=a=>l.createProductService())},Ue)])])])],8,ke)],64)}const Le=E(te,[["render",Ge]]);U(Le).mount("#app");
