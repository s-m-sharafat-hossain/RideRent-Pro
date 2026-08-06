import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';
import 'package:provider/provider.dart';
import '../../services/auth_service.dart';
import '../../services/api_service.dart';
import '../../utils/app_colors.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();
  String _selectedRole = 'customer';
  bool _isLoading = false;
  String? _errorMessage;

  @override
  void dispose() {
    _usernameController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final apiService = Provider.of<ApiService>(context, listen: false);
      final authService = Provider.of<AuthService>(context, listen: false);

      final response = await apiService.login(
        _usernameController.text,
        _passwordController.text,
        _selectedRole,
      );

      if (kDebugMode) {
        print('Login response: $response');
      }

      if (response['success'] == true) {
        // Save auth data based on role
        String userId = '';
        String userName = '';

        switch (_selectedRole) {
          case 'customer':
            userId = response['customer_id'].toString();
            userName = response['full_name'] ?? '';
            break;
          case 'driver':
            userId = response['driver_id'].toString();
            userName = response['full_name'] ?? '';
            break;
          case 'owner':
            userId = response['owner_id'].toString();
            userName = response['full_name'] ?? '';
            break;
          case 'admin':
            userId = response['admin_id'].toString();
            userName = response['username'] ?? '';
            break;
        }

        await authService.saveAuthData(
          userId: userId,
          userRole: _selectedRole,
          userName: userName,
        );

        if (!mounted) return;

        // Navigate to appropriate dashboard
        switch (_selectedRole) {
          case 'customer':
            Navigator.pushReplacementNamed(context, '/customer/dashboard');
            break;
          case 'driver':
            Navigator.pushReplacementNamed(context, '/driver/dashboard');
            break;
          case 'owner':
            Navigator.pushReplacementNamed(context, '/owner/dashboard');
            break;
          case 'admin':
            Navigator.pushReplacementNamed(context, '/admin/dashboard');
            break;
        }
      } else {
        setState(() {
          _errorMessage = response['message'] ?? 'Login failed';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Login failed: ${e.toString()}';
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: AppColors.gradientPrimary,
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24.0),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Icon(
                      Icons.directions_car,
                      size: 80,
                      color: AppColors.white,
                    ),
                    const SizedBox(height: 20),
                    Text(
                      'RideRent Pro',
                      style:
                          Theme.of(context).textTheme.headlineMedium?.copyWith(
                                fontWeight: FontWeight.bold,
                                color: AppColors.white,
                              ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 10),
                    Text(
                      'Login to continue',
                      style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                            color: AppColors.white,
                          ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 40),

                    // Form Card
                    Card(
                      elevation: 8,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Padding(
                        padding: const EdgeInsets.all(24.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.stretch,
                          children: [
                            if (_errorMessage != null)
                              Container(
                                padding: const EdgeInsets.all(12),
                                decoration: BoxDecoration(
                                  color: AppColors.accentRed.withOpacity(0.1),
                                  borderRadius: BorderRadius.circular(8),
                                  border:
                                      Border.all(color: AppColors.accentRed),
                                ),
                                child: Row(
                                  children: [
                                    Icon(Icons.error_outline,
                                        color: AppColors.accentRed),
                                    const SizedBox(width: 8),
                                    Expanded(
                                      child: Text(
                                        _errorMessage!,
                                        style: TextStyle(
                                            color: AppColors.accentRed),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            if (_errorMessage != null)
                              const SizedBox(height: 20),
                            TextFormField(
                              controller: _usernameController,
                              decoration: const InputDecoration(
                                labelText: 'Username',
                                prefixIcon: Icon(Icons.person),
                                border: OutlineInputBorder(),
                              ),
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Please enter your username';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16),
                            TextFormField(
                              controller: _passwordController,
                              obscureText: true,
                              decoration: const InputDecoration(
                                labelText: 'Password',
                                prefixIcon: Icon(Icons.lock),
                                border: OutlineInputBorder(),
                              ),
                              validator: (value) {
                                if (value == null || value.isEmpty) {
                                  return 'Please enter your password';
                                }
                                return null;
                              },
                            ),
                            const SizedBox(height: 16),
                            DropdownButtonFormField<String>(
                              initialValue: _selectedRole,
                              decoration: const InputDecoration(
                                labelText: 'Login as',
                                prefixIcon: Icon(Icons.badge),
                                border: OutlineInputBorder(),
                              ),
                              items: const [
                                DropdownMenuItem(
                                    value: 'customer', child: Text('Customer')),
                                DropdownMenuItem(
                                    value: 'driver', child: Text('Driver')),
                                DropdownMenuItem(
                                    value: 'owner',
                                    child: Text('Vehicle Owner')),
                                DropdownMenuItem(
                                    value: 'admin', child: Text('Admin')),
                              ],
                              onChanged: (value) {
                                setState(() {
                                  _selectedRole = value!;
                                });
                              },
                            ),
                            const SizedBox(height: 24),
                            ElevatedButton(
                              onPressed: _isLoading ? null : _login,
                              style: ElevatedButton.styleFrom(
                                padding:
                                    const EdgeInsets.symmetric(vertical: 16),
                                backgroundColor: AppColors.primary,
                                foregroundColor: AppColors.white,
                              ),
                              child: _isLoading
                                  ? const SizedBox(
                                      height: 20,
                                      width: 20,
                                      child: CircularProgressIndicator(
                                        strokeWidth: 2,
                                        valueColor:
                                            AlwaysStoppedAnimation<Color>(
                                                Colors.white),
                                      ),
                                    )
                                  : const Text('Login',
                                      style: TextStyle(fontSize: 16)),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),

                    // Register Link
                    TextButton(
                      onPressed: () {
                        Navigator.pushNamed(context, '/register');
                      },
                      child: Text(
                        "Don't have an account? Register",
                        style: TextStyle(
                          color: AppColors.white,
                          fontSize: 16,
                        ),
                      ),
                    ),
                    const SizedBox(height: 8),
                    // Forgot Password Link
                    TextButton(
                      onPressed: () {
                        Navigator.pushNamed(context, '/forgot-password');
                      },
                      child: Text(
                        "Forgot Password?",
                        style: TextStyle(
                          color: AppColors.white,
                          fontSize: 14,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
