



@include('partials.newheader')
<body class="mainbg">
	<style type="text/css">
		a{    color: #333!important;}
	</style>
    <div class="infoheader">
        <div class="weui-pt30 weui-pb30 container">
            @if(Auth::user())
<nav class="navbar navbar-default" role="navigation">
    <div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class=" weui-fl logo" href="#"><img src="/images/logo.png"></a>
        </div>
        


        <div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
              <ul class="nav navbar-nav navbar-nav01 weui-f16">
                <li><a href="{!! url('') !!}">首頁</a></li>
                <li><a href="{!! url('dashboard/search') !!}">搜索</a></li>
                <li><a href="{!! url('dashboard') !!}">個人資料</a></li>
                <li><a href="{!! url('dashboard/chat') !!}">收信夾</a></li>
                <li><a href="{!! url('dashboard/board') !!}">留言板</a></li>
                @if ($user->isVip())
                <li><a href="{!! url('dashboard/history') !!}">足跡</a></li>
                <li><a href="{!! url('dashboard/fav') !!}">我的收藏</a></li>
                @endif
                @if (!$user->isVip())
                <li><a href="{!! url('dashboard/upgrade') !!}">升級VIP</a></li>
                @else
                <li><a href="{!! url('dashboard/cancel') !!}">取消VIP</a></li>
                @endif
            </ul>
            <div class="weui-fr weui-f16 weui-pt15">
                <a  href="{!! url('dashboard/chat') !!}" class="weui-dnb weui-pl20 ">
                    <img src="/images/gerenzhongxin_09.png"> <span class="weui-v_m weui-pl5">消息</span>
                    <span class="badge badge_red">{{ \App\Models\Message::unread($user->id) }}</span>
                </a>
                <a href="{!! url('dashboard') !!}" class="weui-dnb weui-pl20 ">
                    <img src="/images/gerenzhongxin_06.png"> <span class="weui-v_m weui-pl5">我的</span>
                </a>
                <!-- <a href="{!! url('logout') !!}" class="weui-dnb weui-pl20 ">
                    <img src="/images/shouye_06.png" > <span class="weui-v_m weui-pl5">Logout</span> 
                </a> -->
            </div>
        </div>

    </div>
</nav>
@else


<nav class="navbar navbar-default" role="navigation">
    <div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class=" weui-fl logo" href="#"><img src="/images/logo.png"></a>
        </div>
        <div class="collapse navbar-collapse weui-fr" id="example-navbar-collapse">
            <ul class="nav navbar-nav weui-f16">
                <li><a href="{!! url('register') !!}"><img src="/images/shouye_11.png"> <span class="weui-v_m weui-pl10">注册</span></a></li>
                <li><a href="{!! url('login') !!}"><img src="/images/shouye_06.png"> <span class="weui-v_m weui-pl10">登入</span></a></li>
                <li><a href="#"><img src="/images/shouye_08.png"> <span class="weui-v_m weui-pl10"> 台湾</span></a></li>
            </ul>
        </div>
    </div>
</nav>
@endif
        </div>  
    </div>


 <div class="minh2 weui-pb30"> 
        <div class="email zzdh">
            <h2 class=" weui-t_c">使用條款</h2>
            <div class="weui-p20 ">
                Taiwan-Sugar Terms of Use Agreement <p>By using the Taiwan-Sugar.net Website (the "Website") you agree to be bound by these Terms of Use (this "Agreement"), whether or not you register as a member ("Member"). If you do not accept these Terms and Conditions or you do not meet or comply with their provisions, you may not use the Website.

</p></li>

<h3>1. Binding Agreement</h3><p>This Agreement sets out the legally binding terms of your use of the Website and your membership in the Service and may be modified by Taiwan-Sugar.net from time to time, such modifications to be effective upon posting by Taiwan-Sugar.net on the Website. This Agreement includes Taiwan-Sugar.net's Acceptable Use Policy for Content Posted on the Website, Taiwan-Sugar.net's Privacy Policy, and any notices regarding the Website.</p></li>

<h3>2. Eligibility</h3><p>You must be eighteen or over to register as a member of Taiwan-Sugar.net or use the Website. Membership in the Service is void where prohibited. By using the Website, you represent and warrant that you have the right, authority, and capacity to enter into this Agreement and to abide by all of the terms and conditions of this Agreement. </p></li>

<h3>3. Term</h3><P>This Agreement will remain in full force and effect while you use the Website and/or are a Member. You may terminate your membership at any time, for any reason by following the instructions on the Cancel Membership or Delete Account or Resign pages in Account Settings. Taiwan-Sugar.net may terminate your membership for any reason, effective upon sending notice to you at the email address you provide in your application for membership, or such other email address as you may later provide to Taiwan-Sugar.net. If Taiwan-Sugar.net terminates your membership in the Service because you have breached the Agreement, you will not be entitled to any refund of unused subscription fees or credits. Even after membership is terminated, this Agreement will remain in effect. Even after this Agreement is terminated, certain provisions will remain in effect, including but not limited to sections 4, 5, 6, 7, 10, 12, 19, 20, 22, 23 and 25 of this Agreement. </P></li>

<h3>4. Proprietary Rights</h3><P>Taiwan-Sugar.net owns and retains all proprietary rights in the Website and the Service. The Website contains the copyrighted material, trademarks, and other proprietary information of Taiwan-Sugar.net, and its licensors. Except for that information which is in the public domain or for which you have been given written permission, you may not copy, modify, publish, transmit, distribute, perform, display, or sell any such proprietary information. </P></li>

<h3>5. Copyright Policy</h3><p>You may not post, distribute, or reproduce in any way any copyrighted material, trademarks, or other proprietary information without obtaining the prior written consent of the owner of such proprietary rights. Without limiting the foregoing, if you believe that your work has been copied and posted on the Service in a way that constitutes copyright infringement, please provide our Copyright Agent with the following information: an electronic or physical signature of the person authorized to act on behalf of the owner of the copyright interest; a description of the copyrighted work that you claim has been infringed; a description of where the material that you claim is infringing is located on the Website; your address, telephone number, and email address; a written statement by you that you have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law; a statement by you, made under penalty of perjury, that the above information in your notice is accurate and that you are the copyright owner or authorized to act on the copyright owner's behalf. </p><li>

<h3>6. Subscription</h3><P>Taiwan-Sugar.net reserves the right to offer free membership to any person(s) of our choice at any given time, for any duration, while charging other members, at the same time. Taiwan-Sugar.net also reserves the right to cancel the FREE trial period at any time, for all or any of its members.</p></li>

<h3>7. Member Disputes</h3><p>You are solely responsible for your interactions with other Taiwan-Sugar.net Members. Taiwan-Sugar.net, Inc. reserves the right, but has no obligation, to monitor disputes between you and other Members. </p></li>

<h3>8. Service</h3><p>Taiwan-Sugar.net does not guarantee, at any time, either their paid or free membership holders, or members who have purchased credits, that the website will be fully operational all the time. Members may face significant service disturbances. Only in the event that www.Taiwan-Sugar.net is offline for a period of more than 96 hours, will a pro-rated refund be given. Taiwan-Sugar.net DOES NOT OFFER TECHNICAL SUPPORT. We will offer support only for services/features related to the website. </p></li>

<h3>9. U.S. Export Controls</h3><p>Software from this Website (the "Software") is further subject to United States export controls. No Software may be downloaded from the Website or otherwise exported or re-exported (i) into (or to a national or resident of) Cuba, Iraq, Libya, North Korea, Iran, Syria, or any other Country to which the U.S. has embargoed goods; or (ii) to anyone on the U.S. Treasury Department's list of Specially Designated Nationals or the U.S. Commerce Department's Table of Deny Orders. By downloading or using the Software, you represent and warrant that you are not located in, under the control of, or a national or resident of any such country or on any such list. </p></li>

<h3>10. Disputes</h3><p>If there is any dispute about or involving the Website and/or the Service, by using the Website, you agree that the dispute will be governed by the laws of Samoa without regard to its conflict of law provisions. You agree to personal jurisdiction by and venue of Samoa.</p></li>

<h3>11. Class Action Waiver</h3><p>Any proceedings to resolve or litigate any dispute will be conducted solely on an individual basis. Neither you nor we will seek to have any dispute heard as a class action or in any other proceeding in which either party acts or proposes to act in any representative capacity. You and we further agree that no arbitration or proceeding will be combined with another without the prior written consent of all parties to the affected proceedings. </p></li>

<h3>12. Other</h3><p>This Agreement, accepted upon use of the Website and further affirmed by becoming a Member of the Service, contains the entire agreement between you and Taiwan-Sugar.net regarding the use of the Website and/or the Service. If any provision of this Agreement is held invalid, the remainder of this Agreement shall continue in full force and effect.</p></li>

<h3>13. Ownership</h3><p>This Website is owned and operated by Taiwan-Sugar.net, and unless otherwise noted, Taiwan-Sugar.net owns the Copyright in all materials provided here. No material from this Website may be copied, modified, reproduced, republished, uploaded, posted, transmitted, or distributed in any manner or form except as explicitly provided below. Modification of the materials or use of the materials for any purpose other than those purposes explicitly permitted below is a violation of Taiwan-Sugar.net's copyright and/or other proprietary rights. You shall not copy or adapt the HTML code that is used to generate this Website. The use of any materials from this Website on any other website or networked computer environment is prohibited unless permission is obtained in writing from Taiwan-Sugar.net. The Website itself, including all textual and graphic content, is copyrighted by Taiwan-Sugar.net, with all rights reserved. </p></li>

<h3>14. Non Commercial Use by Members</h3><p>Taiwan-Sugar.net is for the personal use of individual Members only and may not be used in connection with any commercial endeavors. Organizations, companies, agencies, and/or businesses may not become Members and should not use the Service or the Website for any purpose. Illegal and/or unauthorized uses of the Website, including collecting usernames and/or email addresses of members by electronic or other means for the purpose of sending unsolicited email and unauthorized framing of or linking to the Website will be investigated, and appropriate legal action will be taken, including without limitation, civil, criminal, and injunctive redress. </p></li>

<h3>15. Member Information</h3><p>In consideration of your use of the Website, you agree to provide true, accurate, current and complete information about yourself. You agree to maintain and promptly update your information to keep it true, accurate, current and complete. Taiwan-Sugar.net reserves the right to verify any member identity and to terminate your account if we have grounds to suspect that information provided by you is untrue, inaccurate, not current or incomplete. If you provide untrue information about yourself, please note that you could be guilty of fraud and subject to civil and criminal penalties under U.S. federal and state law (or similar laws in the country of your residence). For example, Title 18, Section 1343 of the U.S. Code authorizes fines of up to $250,000 and jail sentences of up to five years for each offense. Taiwan-Sugar.net reserves the right to report violators to appropriate law enforcement authorities and seek prosecution or civil redress to the fullest extent of the law. </p></li>

<h3>16. Use of Materials</h3><p>You may view and download a single copy of the information contained in this Website (the "Materials") on a single computer for your personal, non-commercial internal use only. This is a revocable license, not a transfer of title, and is subject to the following restrictions: you may not (i) modify the Materials or use them for any commercial purpose, or any public display, performance, sale or rental; (ii) decompile, reverse engineer, or disassemble the Materials; (iii) remove any copyright, trademark registration, or other proprietary notices from the Materials; or (iv) transfer the Materials to another person.</p></li>

<h3>17. Acceptable Website Use</h3><p>General Rules: You may not use this Website in order to transmit, distribute, store or destroy material (i) in violation of any applicable law or regulation, (ii) in a manner that will infringe the copyright, trademark, trade secret or other intellectual property rights of others or violate the privacy, publicity or other personal rights of others, or (iii) in a manner that is defamatory, obscene, threatening, abusive or hateful.

</p>

<p>Website Security Rules: You are prohibited from violating or attempting to violate the security of this Website, including, without limitation, (i) accessing data not intended for you or logging into a server or account which you are not authorized to access, (ii) attempting to probe, scan, or test the vulnerability of a system or network or to breach security or authentication measures without proper authorization, (iii) attempting to interfere with service to any user, host, or network, including, without limitation, by way of submitting a virus to this Website, overloading, "flooding", "spamming", "mail bombing", or "crashing", (iv) sending unsolicited e-mail, including promotions and/or advertising of products or services, or (v) forging any TCP/IP packet header or any part of the header information in any e-mail or newsgroup posting. Violations of system or network security may result in civil or criminal liability. Taiwan-Sugar.net will investigate occurrences which may involve such violations and may involve, and cooperate with, law enforcement authorities in prosecuting users who are involved in such violations.</p> <li>

<h3>18. Specific Prohibited Uses</h3><p>This Website may be used only for lawful purposes by individuals seeking friendship and love. As one of the conditions of your use of this Website, you represent, warrant, and agree that you will not use (or plan, encourage, or help others to use) this Website for any purpose or in any manner that is prohibited by these terms, conditions and notices or by applicable law (including but not limited to any applicable export controls). It is your responsibility to ensure that your use of this Website complies with these terms and conditions (the "Terms and Conditions") and to seek prior written consent from Taiwan-Sugar.net for any uses not permitted or not expressly specified herein. Taiwan-Sugar.net specifically prohibits any use of this Website, and you hereby agree not to use this Website, for any of the following:

</p>

<p>•(i) Posting any incomplete, false or inaccurate information about yourself and/or your profile; •	(ii) Posting any material that is not entirely your own or for which you do not have full rights to use; •	(iii) Deleting or otherwise revising any material posted by any other person; •	(iv) Using any device, software, or routine to interfere (or attempt to interfere) with the proper working of this Website or any activity being conducted on this Website; •	(v) Taking any action that imposes an unreasonable or disproportionately large load on this Website's infrastructure (including without limitation "flooding," "spamming," "mail bombing," or "crashing" the Website); •	(vi) Notwithstanding anything to the contrary contained herein, using (or attempting to use) any engine, software tool, agent, or other device or mechanism (including without limitation browsers, spiders, robots, avatars, or intelligent agents) to navigate or search this Website other than the search engine and search agents available from Taiwan-Sugar.net on this Website and other than generally available third party Web browsers (e.g., Netscape Navigator, Microsoft Explorer, etc.). Taiwan-Sugar.net considers more than 100 detailed profile views per user license per day to be abuse, and Taiwan-Sugar.net reserves the right to terminate access to the product and collect full contract value in the event of such abuse; •	(vii) Accessing data not intended for you or logging into a server or account that you are not authorized to access; •	(viii) Probing, scanning, or testing the vulnerability of a system or network or to breach security or authentication measures without proper authorization; •	(ix) Forging any TCP/IP packet header or any part of the header information in any e-mail or newsgroup posting; •	(x) If you have a password, allowing any other person to access a non-public area of this Website, disclosing to or sharing your password with any third parties or using your password for any unauthorized purpose; •	(xi) Attempting to decipher, decompile, disassemble or reverse engineer any of the software comprising or in any way making up a part of this Website; •	(xii) Posting or sending material that exploits people under the age of 18 in a sexual or violent manner, or solicits personal information from anyone under 18; •	(xiii) Soliciting passwords or personal identifying information for commercial or unlawful purposes from other users; and •	(xiv) Engage in advertising to, or solicitation of, other Members to send money, buy or sell any products or services. You may not transmit any chain letters or junk email to other Members. Although Taiwan-Sugar.net cannot monitor the conduct of its Members off the Website, it is also a violation of these rules to use any information obtained from the Service in order to harass, abuse, or harm another person, or in order to contact, advertise to, solicit, or sell to any Member without their prior explicit consent. •	(xv) Soliciting sex in exchange for money, or prostitution. •	(xvi) Using our website as an escort, or using our service to solicit clients for your escort service. •	(xvii) Initiating contact with members off site by any other means, without first obtaining explicit permission from them to do so via our website. </p></li>

<h3>19. No Resale or Unauthorized Use</h3><p>You agree not to resell or assign your rights or obligations under these Terms and Conditions. You also agree not to make any unauthorized commercial use of this Website.</p></li>

<h3>20. No Refund Policy</h3><p>Taiwan-Sugar.net maintains a strict no-refund policy. All purchases made on our website are final. If you choose to purchase a credit or subscription package on Taiwan-Sugar.net, you agree that absolutely no refunds, either full or partial, will be issued for any reasons or for any unused credits or subscription. With the exception of our Guarantee Package, you agree the purchase of any credit or subscription package on our website does not guarantee you will get a date every time you spend credits to unlock an offer or conversation. Further, you agree that no refunds will be made on any credits spent to unlock an offer or conversation, even if the offer or conversation does not result in an actual meeting or date. </p></li>

<h3>21. Chargebacks and Collections</h3><p>You agree that all payments made on Taiwan-Sugar.net are final, and you will not challenge or dispute the charge with your bank. You further agree that should you have any issues relating to your payment (such as duplicate billing), you will open a support ticket through our website to resolve the issue. Should a chargeback or dispute be initiated with your bank, you agree that you will be held responsible for any outstanding balance owed to us plus any chargeback fees charged by our bank, which may be as much as US$60. You agree that you will pay any outstanding balance you have with Taiwan-Sugar.net within 30 days from the date of notification. Any outstanding balance left unpaid after 30 days will be submitted to a collections agency, and you agree that a collections fee of up to 50% of the outstanding balance or $100 (whichever is higher) will be added to the amount that you owe. </p></li>

<h3>22. Account Deletion, Suspension and Reinstatement</h3><p>When you delete your Taiwan-Sugar.net account or when your account is suspended for violating the policies or Terms of Use of the website, you agree that any unused subscription or credits will be forfeited and no refunds will be issued for any unused credits or membership subscription. Further, if your deleted Taiwan-Sugar.net account is reinstated at a later date, you agree that any unused credits or membership subscription you have remaining at the time of your account deletion will not be reinstated with your account. </p></li>

<h3>23. No Credit or Subscription Transfer</h3><p>Credits or subscription purchased for a specific Taiwan-Sugar.net account is strictly non-transferable. You agree that any remaining credits or membership subscription associated with an active or deleted Taiwan-Sugar.net account will not be transferred to any other account belonging to you or someone else. </p></li>

<h3>24. Non-Disparagement</h3><p>As a condition of using Taiwan-Sugar.net, you agree not to, directly or indirectly, in any capacity or manner, make, express, transmit speak, write, verbalize or otherwise communicate in any way (or cause, further, assist, solicit, encourage, support or participate in any of the foregoing), any remark, comment, message, information, declaration, communication or other statement of any kind, whether verbal, in writing, electronically transferred or otherwise, that might reasonably be construed to be derogatory or critical of, or negative toward, Taiwan-Sugar.net, or any of its directors, officers, affiliates, subsidiaries, employees, agents or representatives. </p></li>

<h3>25. User Submissions</h3><p>We appreciate hearing from our customers and welcome your comments regarding our services and this Website. Please be advised, however, that our policy does not permit us to accept or consider creative ideas, suggestions, inventions or materials other than those which we have specifically requested. While we do value your feedback on our services, please be specific in your comments regarding our services and do not submit creative ideas, inventions, suggestions, or materials. If, despite our request, you send us creative suggestions, ideas, drawings, concepts, inventions, or other information (collectively the "Information"), the Information shall be the property of Taiwan-Sugar.net. None of the Information shall be subject to any obligation of confidence on our part and we shall not be liable for any use or disclosure of any Information. Taiwan-Sugar.net shall own exclusively all now known or later discovered rights to the Information and shall be entitled to unrestricted use of the Information for any purpose whatsoever, commercial or otherwise, without compensation to you or any other person who submitted the Information. Furthermore, as a user, you are responsible for your own communications and are responsible for the consequences of their posting. You must not, and by using this Website you hereby agree not to, do the following things: (i) post material that is copyrighted, unless you are the copyright owner or have the permission of the copyright owner to post it; (ii) post material that reveals trade secrets, unless you own them or have the permission of the owner; (iii) post material that infringes on any other intellectual property rights of others or on the privacy or publicity rights of others; (iv) post material that is obscene, defamatory, threatening, harassing, abusive, hateful, or embarrassing to another user or any other person or entity; (v) post a sexually-explicit image or statement; (vi) post advertisements or solicitations of business, post chain letters or pyramid schemes; (vii) impersonate another person; (viii) or post material that contains viruses, Trojan horses, worms, time bombs, cancelbots or other computer programming routines or engines that are intended to damage, detrimentally interfere with, surreptitiously intercept or expropriate any system, data or information. </p></li>

<h3>26. User Information</h3><p>When you register for our service(s) on the Website, you will be asked to provide us with certain information, including but not limited to a valid e-mail address (your "User Information"). Taiwan-Sugar.net's right to use your User Information is described in our privacy policy. Please see our Privacy Policy for further details regarding use of your Information. Taiwan-Sugar.net reserves the right to offer third party services and products to you based on the preferences that you identify in your Information and at any time thereafter; such offers may be made by us or by third parties. </p></li>

<h3>27. Intellectual Property Policy</h3><p>We respect the intellectual property of others and expect you to do the same. At our discretion and in appropriate circumstances, we may terminate your account(s) and/or prevent access to the Website by users who infringe upon the intellectual property rights of others. Pursuant to 17 United States Code 512 (2) (the Digital Millennium Copyright Act of 1998, as amended), you may contact our designated agent for notice of alleged copyright infringement appearing on our site at customer support. To file a notice of infringement with us, you need to fulfill the requirements specified in Title II of the Digital Millennium Copyright Act of 1998. The text of this statute can be found at the U.S. Copyright Office web site, (http://www.copyright.gov/).  </p></li>

<h3>28. Disclaimer</h3><p>Taiwan-Sugar.net is not responsible for any incorrect or inaccurate Content posted on the Website or in connection with the Service, whether caused by users of the Website, Members or by any of the equipment or programming associated with or utilized in the Service. Taiwan-Sugar.net is not responsible for the conduct, whether online or offline, of any user of the Website or Member of the Service. Taiwan-Sugar.net assumes no responsibility for any error, omission, interruption, deletion, defect, delay in operation or transmission, communications line failure, theft or destruction or unauthorized access to, or alteration of, user or Member communications. Taiwan-Sugar.net is not responsible for any problems or technical malfunction of any telephone network or lines, computer online systems, servers or providers, computer equipment, software, failure of email or players on account of technical problems or traffic congestion on the Internet or at any Website or combination thereof, including injury or damage to users and/or Members or to any other person's computer related to or resulting from participating or downloading materials in connection with the Web and/or in connection with the Service. Under no circumstances will Taiwan-Sugar.net be responsible for any loss or damage, including personal injury or death, resulting from anyone's use of the Website or the Service, any Content posted on the Website or transmitted to Members, or any interactions between users of the Website, whether online or offline. The Website and the Service are provided "AS-IS" and Taiwan-Sugar.net expressly disclaims any warranty of fitness for a particular purpose or non-infringement. Taiwan-Sugar.net cannot guarantee and does not promise any specific results from use of the Website and/or the Service. This Website contains links to sites that are not maintained by Taiwan-Sugar.net. While we try to include links only to those sites which are in good taste and safe for our visitors, we are not responsible for the content or accuracy of those sites and cannot guarantee that sites will not change without our knowledge. The inclusion of a link in this Website does not imply our endorsement of the linked site. If you decide to access linked third-party Web sites, you do so at your own risk. This Website is only a venue - it acts as a venue for individuals to post personal and contact information for the purposes of dating. Taiwan-Sugar.net is not required to not screen or censor information posted on the Website, including but not limited to the identity of any user. We are not involved in any actual communication between Members. As a result, we have no control over the quality, safety, or legality of the information or profiles posted, the truth or accuracy of the information. You agree you are solely responsible for your interactions with other Taiwan-Sugar.net Members. Taiwan-Sugar.net, Inc. reserves the right, but has no obligation, to monitor disputes between you and other Members. This includes, but is not limited to, conversations via regular email, dates, relationships, phone calls, meetings, he said/she said accusations or any other correspondance or interaction that occur outside of the scope of this Website. The website is a tool for providing the initial contact between members, anything beyond that is not in our control and is done so at the their own risk. Members have to use common sense about what information they reveal to others via email or any other means of communication. It is your responsibility to investigate matches/members of this site and that you will verify they are legitimate date seekers. There are many different frauds, schemes, and deceptions on the Internet, and we strongly caution you to be skeptical of any of our members until you learn more about them and verify their background. You are solely responsible for your interactions with other Taiwan-Sugar.net Members. You agree that Taiwan-Sugar.net will not be held responsible for any incident following a contact or a date between Members. THIS SITE IS FOR INFORMATIONAL PURPOSES ONLY. THE MATERIALS AND INFORMATION FOUND ON THIS SITE ARE PROVIDED "AS IS," WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTY FOR INFORMATION, SERVICES, OR PRODUCTS PROVIDED THROUGH OR IN CONNECTION WITH THIS SITE AND ANY IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, EXPECTATIONS OF PRIVACY OR NON-INFRINGEMENT. Taiwan-Sugar.net DOES NOT WARRANT THAT THE FUNCTIONS CONTAINED IN THE MATERIALS WILL BE UNINTERRUPTED OR ERROR-FREE, THAT DEFECTS WILL BE CORRECTED, OR THAT THIS SITE OR THE SERVER THAT MAKES IT AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. Taiwan-Sugar.net DOES NOT WARRANT OR MAKE ANY REPRESENTATIONS REGARDING THE USE OR THE RESULTS OF THE USE OF THE MATERIALS IN THIS SITE IN TERMS OF THEIR CORRECTNESS, ACCURACY, RELIABILITY, OR OTHERWISE. YOU (AND NOT Taiwan-Sugar.net) ASSUME THE ENTIRE COST OF ALL NECESSARY SERVICING, REPAIR, OR CORRECTION. APPLICABLE LAW MAY NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSION MAY NOT APPLY TO YOU. This disclaimer of liability applies to any damages or injury caused by any failure of performance, error, omission, interruption, deletion, defect, delay in operation or transmission, computer virus, act of God/act of nature, communication line failure, theft or destruction or unauthorized access to, alteration of, or use of record, whether for breach of contract, tort, negligence, or under any other cause of action. You specifically acknowledge and agree that Taiwan-Sugar.net is not liable for your defamatory, offensive, infringing or illegal materials or conduct or that of third parties, and we reserve the right to remove such materials from this Website without liability. </p></li>

<h3>29. Limitation on Liability</h3><p>Except in jurisdictions where such provisions are restricted, in no event will Taiwan-Sugar.net be liable to you or any third person for any indirect, consequential, exemplary, incidental, special or punitive damages, including also lost profits arising from your use of the Web site or the Service, even if Taiwan-Sugar.net has been advised of the possibility of such damages. Notwithstanding anything to the contrary contained herein, Taiwan-Sugar.net's liability to you for any cause whatsoever, and regardless of the form of the action, will at all times be limited to the amount paid, if any, by you to Taiwan-Sugar.net for the Service during the term of membership. </p></li>

<h3>30. Termination</h3><p>We may terminate this license at any time if, in our sole discretion and judgment, you fail to comply with any term or provision of this Agreement. Upon termination, you shall destroy any materials obtained from this Website and all copies thereof, whether made under the terms of this Agreement or otherwise. </p></li>

<h3>31. Indemnity</h3><p>You agree to defend, indemnify, and hold harmless Taiwan-Sugar.net, its officers, directors, employees, and agents from and against any claims, actions, or demands, including without limitation reasonable legal and accounting fees, arising from your use of the materials or your breach of the terms of this Agreement. Taiwan-Sugar.net shall provide notice to you of any such claim, suit, or proceeding and shall assist you, at your expense and in our discretion, in defending any such claim, suit or proceeding. </p></li>



            </div>
        </div>
    
    </div>


 @include('partials.newfooter')
        @include('partials.newscripts')
</body>
</html>
