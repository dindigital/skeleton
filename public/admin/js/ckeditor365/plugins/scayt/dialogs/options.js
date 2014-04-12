﻿/*
 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

        CKEDITOR.dialog.add('scaytcheck', function(a) {
          var b = true, c, d = CKEDITOR.document, e = a.name, f = CKEDITOR.plugins.scayt.getUiTabs(a), g, h = [], i = 0, j = ['dic_create_' + e + ',dic_restore_' + e, 'dic_rename_' + e + ',dic_delete_' + e], k = ['mixedCase', 'mixedWithDigits', 'allCaps', 'ignoreDomainNames'];
          function l() {
            if (typeof document.forms['optionsbar_' + e] != 'undefined')
              return document.forms['optionsbar_' + e].options;
            return[];
          }
          ;
          function m() {
            if (typeof document.forms['languagesbar_' + e] != 'undefined')
              return document.forms['languagesbar_' + e].scayt_lang;
            return[];
          }
          ;
          function n(z, A) {
            if (!z)
              return;
            var B = z.length;
            if (B == undefined) {
              z.checked = z.value == A.toString();
              return;
            }
            for (var C = 0; C < B; C++) {
              z[C].checked = false;
              if (z[C].value == A.toString())
                z[C].checked = true;
            }
          }
          ;
          var o = a.lang.scayt, p = [{id: 'options', label: o.optionsTab, elements: [{type: 'html', id: 'options', html: '<form name="optionsbar_' + e + '"><div class="inner_options">' + '\t<div class="messagebox"></div>' + '\t<div style="display:none;">' + '\t\t<input type="checkbox" name="options"  id="allCaps_' + e + '" />' + '\t\t<label for="allCaps" id="label_allCaps_' + e + '"></label>' + '\t</div>' + '\t<div style="display:none;">' + '\t\t<input name="options" type="checkbox"  id="ignoreDomainNames_' + e + '" />' + '\t\t<label for="ignoreDomainNames" id="label_ignoreDomainNames_' + e + '"></label>' + '\t</div>' + '\t<div style="display:none;">' + '\t<input name="options" type="checkbox"  id="mixedCase_' + e + '" />' + '\t\t<label for="mixedCase" id="label_mixedCase_' + e + '"></label>' + '\t</div>' + '\t<div style="display:none;">' + '\t\t<input name="options" type="checkbox"  id="mixedWithDigits_' + e + '" />' + '\t\t<label for="mixedWithDigits" id="label_mixedWithDigits_' + e + '"></label>' + '\t</div>' + '</div></form>'}]}, {id: 'langs', label: o.languagesTab, elements: [{type: 'html', id: 'langs', html: '<form name="languagesbar_' + e + '"><div class="inner_langs">' + '\t<div class="messagebox"></div>\t' + '   <div style="float:left;width:45%;margin-left:5px;" id="scayt_lcol_' + e + '" ></div>' + '   <div style="float:left;width:45%;margin-left:15px;" id="scayt_rcol_' + e + '"></div>' + '</div></form>'}]}, {id: 'dictionaries', label: o.dictionariesTab, elements: [{type: 'html', style: '', id: 'dictionaries', html: '<form name="dictionarybar_' + e + '"><div class="inner_dictionary" style="text-align:left; white-space:normal; width:320px; overflow: hidden;">' + '\t<div style="margin:5px auto; width:80%;white-space:normal; overflow:hidden;" id="dic_message_' + e + '"> </div>' + '\t<div style="margin:5px auto; width:80%;white-space:normal;"> ' + '       <span class="cke_dialog_ui_labeled_label" >Dictionary name</span><br>' + '\t\t<span class="cke_dialog_ui_labeled_content" >' + '\t\t\t<div class="cke_dialog_ui_input_text">' + '\t\t\t\t<input id="dic_name_' + e + '" type="text" class="cke_dialog_ui_input_text"/>' + '\t\t</div></span></div>' + '\t\t<div style="margin:5px auto; width:80%;white-space:normal;">' + '\t\t\t<a style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_create_' + e + '">' + '\t\t\t\t</a>' + '\t\t\t<a  style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_delete_' + e + '">' + '\t\t\t\t</a>' + '\t\t\t<a  style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_rename_' + e + '">' + '\t\t\t\t</a>' + '\t\t\t<a  style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_restore_' + e + '">' + '\t\t\t\t</a>' + '\t\t</div>' + '\t<div style="margin:5px auto; width:95%;white-space:normal;" id="dic_info_' + e + '"></div>' + '</div></form>'}]}, {id: 'about', label: o.aboutTab, elements: [{type: 'html', id: 'about', style: 'margin: 5px 5px;', html: '<div id="scayt_about_' + e + '"></div>'}]}], q = {title: o.title, minWidth: 360, minHeight: 220, onShow: function() {
              var z = this;
              z.data = a.fire('scaytDialog', {});
              z.options = z.data.scayt_control.option();
              z.chosed_lang = z.sLang = z.data.scayt_control.sLang;
              if (!z.data || !z.data.scayt || !z.data.scayt_control) {
                alert('Error loading application service');
                z.hide();
                return;
              }
              var A = 0;
              if (b)
                z.data.scayt.getCaption(a.langCode || 'en', function(B) {
                  if (A++ > 0)
                    return;
                  c = B;
                  s.apply(z);
                  t.apply(z);
                  b = false;
                });
              else
                t.apply(z);
              z.selectPage(z.data.tab);
            }, onOk: function() {
              var z = this.data.scayt_control;
              z.option(this.options);
              var A = this.chosed_lang;
              z.setLang(A);
              z.refresh();
            }, onCancel: function() {
              var z = l();
              for (var A in z)
                z[A].checked = false;
              n(m(), '');
            }, contents: h}, r = CKEDITOR.plugins.scayt.getScayt(a);
          for (g = 0; g < f.length; g++) {
            if (f[g] == 1)
              h[h.length] = p[g];
          }
          if (f[2] == 1)
            i = 1;
          var s = function() {
            var z = this, A = z.data.scayt.getLangList(), B = ['dic_create', 'dic_delete', 'dic_rename', 'dic_restore'], C = [], D = [], E = k, F;
            if (i) {
              for (F = 0; F < B.length; F++) {
                C[F] = B[F] + '_' + e;
                d.getById(C[F]).setHtml('<span class="cke_dialog_ui_button">' + c['button_' + B[F]] + '</span>');
              }
              d.getById('dic_info_' + e).setHtml(c.dic_info);
            }
            if (f[0] == 1)
              for (F in E) {
                var G = 'label_' + E[F], H = G + '_' + e, I = d.getById(H);
                if ('undefined' != typeof I && 'undefined' != typeof c[G] && 'undefined' != typeof z.options[E[F]]) {
                  I.setHtml(c[G]);
                  var J = I.getParent();
                  J.$.style.display = 'block';
                }
              }
            var K = '<p><img src="' + window.scayt.getAboutInfo().logoURL + '" /></p>' + '<p>' + c.version + window.scayt.getAboutInfo().version.toString() + '</p>' + '<p>' + c.about_throwt_copy + '</p>';
            d.getById('scayt_about_' + e).setHtml(K);
            var L = function(U, V) {
              var W = d.createElement('label');
              W.setAttribute('for', 'cke_option' + U);
              W.setHtml(V[U]);
              if (z.sLang == U)
                z.chosed_lang = U;
              var X = d.createElement('div'), Y = CKEDITOR.dom.element.createFromHtml('<input id="cke_option' + U + '" type="radio" ' + (z.sLang == U ? 'checked="checked"' : '') + ' value="' + U + '" name="scayt_lang" />');
              Y.on('click', function() {
                this.$.checked = true;
                z.chosed_lang = U;
              });
              X.append(Y);
              X.append(W);
              return{lang: V[U], code: U, radio: X};
            };
            if (f[1] == 1) {
              for (F in A.rtl)
                D[D.length] = L(F, A.ltr);
              for (F in A.ltr)
                D[D.length] = L(F, A.ltr);
              D.sort(function(U, V) {
                return V.lang > U.lang ? -1 : 1;
              });
              var M = d.getById('scayt_lcol_' + e), N = d.getById('scayt_rcol_' + e);
              for (F = 0; F < D.length; F++) {
                var O = F < D.length / 2 ? M : N;
                O.append(D[F].radio);
              }
            }
            var P = {};
            P.dic_create = function(U, V, W) {
              var X = W[0] + ',' + W[1], Y = c.err_dic_create, Z = c.succ_dic_create;
              window.scayt.createUserDictionary(V, function(aa) {
                x(X);
                w(W[1]);
                Z = Z.replace('%s', aa.dname);
                v(Z);
              }, function(aa) {
                Y = Y.replace('%s', aa.dname);
                u(Y + '( ' + (aa.message || '') + ')');
              });
            };
            P.dic_rename = function(U, V) {
              var W = c.err_dic_rename || '', X = c.succ_dic_rename || '';
              window.scayt.renameUserDictionary(V, function(Y) {
                X = X.replace('%s', Y.dname);
                y(V);
                v(X);
              }, function(Y) {
                W = W.replace('%s', Y.dname);
                y(V);
                u(W + '( ' + (Y.message || '') + ' )');
              });
            };
            P.dic_delete = function(U, V, W) {
              var X = W[0] + ',' + W[1], Y = c.err_dic_delete, Z = c.succ_dic_delete;
              window.scayt.deleteUserDictionary(function(aa) {
                Z = Z.replace('%s', aa.dname);
                x(X);
                w(W[0]);
                y('');
                v(Z);
              }, function(aa) {
                Y = Y.replace('%s', aa.dname);
                u(Y);
              });
            };
            P.dic_restore = z.dic_restore || (function(U, V, W) {
              var X = W[0] + ',' + W[1], Y = c.err_dic_restore, Z = c.succ_dic_restore;
              window.scayt.restoreUserDictionary(V, function(aa) {
                Z = Z.replace('%s', aa.dname);
                x(X);
                w(W[1]);
                v(Z);
              }, function(aa) {
                Y = Y.replace('%s', aa.dname);
                u(Y);
              });
            });
            function Q(U) {
              var V = d.getById('dic_name_' + e).getValue();
              if (!V) {
                u(' Dictionary name should not be empty. ');
                return false;
              }
              try {
                var W = U.data.getTarget().getParent(), X = /(dic_\w+)_[\w\d]+/.exec(W.getId())[1];
                P[X].apply(null, [W, V, j]);
              } catch (Y) {
                u(' Dictionary error. ');
              }
              return true;
            }
            ;
            var R = (j[0] + ',' + j[1]).split(','), S;
            for (F = 0, S = R.length; F < S; F += 1) {
              var T = d.getById(R[F]);
              if (T)
                T.on('click', Q, this);
            }
          }, t = function() {
            var z = this;
            if (f[0] == 1) {
              var A = l();
              for (var B = 0, C = A.length; B < C; B++) {
                var D = A[B].id, E = d.getById(D);
                if (E) {
                  A[B].checked = false;
                  if (z.options[D.split('_')[0]] == 1)
                    A[B].checked = true;
                  if (b)
                    E.on('click', function() {
                      z.options[this.getId().split('_')[0]] = this.$.checked ? 1 : 0;
                    });
                }
              }
            }
            if (f[1] == 1) {
              var F = d.getById('cke_option' + z.sLang);
              n(F.$, z.sLang);
            }
            if (i) {
              window.scayt.getNameUserDictionary(function(G) {
                var H = G.dname;
                x(j[0] + ',' + j[1]);
                if (H) {
                  d.getById('dic_name_' + e).setValue(H);
                  w(j[1]);
                } else
                  w(j[0]);
              }, function() {
                d.getById('dic_name_' + e).setValue('');
              });
              v('');
            }
          };
          function u(z) {
            d.getById('dic_message_' + e).setHtml('<span style="color:red;">' + z + '</span>');
          }
          ;
          function v(z) {
            d.getById('dic_message_' + e).setHtml('<span style="color:blue;">' + z + '</span>');
          }
          ;
          function w(z) {
            z = String(z);
            var A = z.split(',');
            for (var B = 0, C = A.length; B < C; B += 1)
              d.getById(A[B]).$.style.display = 'inline';
          }
          ;
          function x(z) {
            z = String(z);
            var A = z.split(',');
            for (var B = 0, C = A.length; B < C; B += 1)
              d.getById(A[B]).$.style.display = 'none';
          }
          ;
          function y(z) {
            d.getById('dic_name_' + e).$.value = z;
          }
          ;
          return q;
        });
