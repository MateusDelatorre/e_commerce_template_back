package com.example.demo.domain.entities.user;

import java.util.List;
import java.util.Optional;

public interface IUserRepository {
    User save(User user);
    boolean delete(Long id);
    Optional<User> findById(Long id);
    Optional<User> findByEmail(String email);
    List<User> searchByName(String nameFragment);
    List<User> searchByEmail(String emailFragment);
    void delete(Long id);
}
