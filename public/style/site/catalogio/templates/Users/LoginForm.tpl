            <!-- Login -->
            <div id="signup">
                <h3>Вход для пользователей</h3>
                
                    <div class="in">
                    <form action="" method="get">
                        <table class="nom">
                            <tr>
                                <td><label for="inp-user">Логин:</label></td>
                                <td colspan="2"><input type="text" size="30" style="width:190px;" name="" id="inp-user" /></td>
                            </tr>
                            <tr>
                                <td><label for="inp-pass">Пароль:</label></td>
                                <td colspan="2"><input type="text" size="30" style="width:190px;" name="" id="inp-pass" /></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="smaller">
                                    <input type="checkbox" name="" id="inp-remember" /> 
                                    <label for="inp-remember" title="Запомнить меня на 14 дней" class="help">Запомнить меня</label>
                                </td>
                                <td class="t-right">
                                    <input type="image" value="Login" src="{$smarty.const.PTA_DESIGN_IMAGES_URL}/signup-button.gif" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    </div> <!-- /in -->
                    
                    <div class="in02">
                    <ul class="nom">
                        <li class="ico-reg"><strong><a href="">Регистрация</a></strong></li>
                        <li class="ico-send"><a href="">Забыли пароль?</a></li>
                    </ul>
                    </div> <!-- /in02 -->
                    
            </div> <!-- /signup -->
            <hr class="noscreen" />
            <div id="signup-bottom"></div>
